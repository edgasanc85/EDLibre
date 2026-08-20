<?php

namespace App\Services;

use App\Models\Concertacion;
use App\Models\Evaluacion;
use App\Models\EvaluacionComportamental;
use App\Models\EvaluacionCompromiso;

class EvaluacionConsolidacionService
{
    /**
     * Genera o actualiza la Consolidación Definitiva (Evaluación Final del Periodo) para una concertación.
     */
    public function generarConsolidacionDefinitiva(Concertacion $concertacion): ?Evaluacion
    {
        $evaluacionesPrevias = Evaluacion::with(['evaluacionCompromisos', 'evaluacionComportamentales'])
            ->where('concertacion_id', $concertacion->id)
            ->whereNotIn('causal', ['Consolidación semestral', 'Consolidación definitiva'])
            ->whereIn('estado', ['calificada', 'aceptada'])
            ->active()
            ->get();

        if ($evaluacionesPrevias->isEmpty()) {
            return null;
        }

        $totalDias = 0;
        $sumaFuncionalPonderada = 0;
        $sumaComportamentalPonderada = 0;

        $sumaCompromisoPonderada = [];
        $totalDiasCompromiso = [];

        $sumaConductaPonderada = [];
        $totalDiasConducta = [];

        $minFechaInicio = null;
        $maxFechaFin = null;

        foreach ($evaluacionesPrevias as $ep) {
            $dias = $ep->diasEvaluados();
            if ($dias <= 0) {
                continue;
            }

            $totalDias += $dias;
            $sumaFuncionalPonderada += ($ep->puntaje_funcional_obtenido * $dias);
            $sumaComportamentalPonderada += ($ep->puntaje_comportamental_obtenido * $dias);

            if (! $minFechaInicio || ($ep->periodo_evaluado_inicio && $ep->periodo_evaluado_inicio < $minFechaInicio)) {
                $minFechaInicio = $ep->periodo_evaluado_inicio;
            }
            if (! $maxFechaFin || ($ep->periodo_evaluado_fin && $ep->periodo_evaluado_fin > $maxFechaFin)) {
                $maxFechaFin = $ep->periodo_evaluado_fin;
            }

            // Ponderar compromisos funcionales
            foreach ($ep->evaluacionCompromisos as $ec) {
                if ($ec->calificacion !== null) {
                    $cid = $ec->compromiso_funcional_id;
                    $sumaCompromisoPonderada[$cid] = ($sumaCompromisoPonderada[$cid] ?? 0) + ($ec->calificacion * $dias);
                    $totalDiasCompromiso[$cid] = ($totalDiasCompromiso[$cid] ?? 0) + $dias;
                }
            }

            // Ponderar conductas comportamentales
            foreach ($ep->evaluacionComportamentales as $ecomp) {
                if ($ecomp->calificacion !== null) {
                    $key = $ecomp->compromiso_comportamental_id.'_'.$ecomp->conducta_id;
                    $sumaConductaPonderada[$key] = ($sumaConductaPonderada[$key] ?? 0) + ($ecomp->calificacion * $dias);
                    $totalDiasConducta[$key] = ($totalDiasConducta[$key] ?? 0) + $dias;
                }
            }
        }

        if ($totalDias <= 0) {
            return null;
        }

        $puntajeFuncional = $sumaFuncionalPonderada / $totalDias;
        $puntajeComportamental = $sumaComportamentalPonderada / $totalDias;

        // Buscar si ya existe una consolidación definitiva para no duplicar
        $consolidacion = Evaluacion::where('concertacion_id', $concertacion->id)
            ->where('causal', 'Consolidación definitiva')
            ->first();

        if ($consolidacion) {
            $consolidacion->update([
                'puntaje_funcional_obtenido' => round($puntajeFuncional, 2),
                'puntaje_comportamental_obtenido' => round($puntajeComportamental, 2),
                'fecha_evaluacion' => now(),
                'periodo_evaluado_inicio' => $minFechaInicio,
                'periodo_evaluado_fin' => $maxFechaFin,
                'activo' => true,
            ]);
        } else {
            $consolidacion = Evaluacion::create([
                'concertacion_id' => $concertacion->id,
                'causal' => 'Consolidación definitiva',
                'estado' => 'calificada', // El evaluado debe aceptarla
                'puntaje_funcional_obtenido' => round($puntajeFuncional, 2),
                'puntaje_comportamental_obtenido' => round($puntajeComportamental, 2),
                'fecha_evaluacion' => now(),
                'periodo_evaluado_inicio' => $minFechaInicio,
                'periodo_evaluado_fin' => $maxFechaFin,
                'activo' => true,
            ]);
        }

        // Sincronizar detalle de compromisos funcionales
        $concertacion->loadMissing(['compromisosFuncionals', 'compromisosComportamentals.conductas']);
        foreach ($concertacion->compromisosFuncionals as $cf) {
            $calif = (isset($totalDiasCompromiso[$cf->id]) && $totalDiasCompromiso[$cf->id] > 0)
                ? round($sumaCompromisoPonderada[$cf->id] / $totalDiasCompromiso[$cf->id], 2)
                : null;

            EvaluacionCompromiso::updateOrCreate(
                [
                    'evaluacion_id' => $consolidacion->id,
                    'compromiso_funcional_id' => $cf->id,
                ],
                [
                    'calificacion' => $calif,
                    'activo' => true,
                ]
            );
        }

        // Sincronizar detalle de compromisos comportamentales
        foreach ($concertacion->compromisosComportamentals as $cc) {
            foreach ($cc->conductas as $conducta) {
                $key = $cc->id.'_'.$conducta->id;
                $califConducta = (isset($totalDiasConducta[$key]) && $totalDiasConducta[$key] > 0)
                    ? round($sumaConductaPonderada[$key] / $totalDiasConducta[$key], 2)
                    : null;

                EvaluacionComportamental::updateOrCreate(
                    [
                        'evaluacion_id' => $consolidacion->id,
                        'compromiso_comportamental_id' => $cc->id,
                        'conducta_id' => $conducta->id,
                    ],
                    [
                        'calificacion' => $califConducta,
                        'activo' => true,
                    ]
                );
            }
        }

        return $consolidacion;
    }

    /**
     * Genera o actualiza la Consolidación Semestral para una concertación.
     */
    public function generarConsolidacionSemestral(Concertacion $concertacion): ?Evaluacion
    {
        $evaluacionesPrevias = Evaluacion::with(['evaluacionCompromisos', 'evaluacionComportamentales'])
            ->where('concertacion_id', $concertacion->id)
            ->whereNotIn('causal', ['Consolidación semestral', 'Consolidación definitiva'])
            ->whereIn('estado', ['calificada', 'aceptada'])
            ->active()
            ->get();

        if ($evaluacionesPrevias->isEmpty()) {
            return null;
        }

        $totalDias = 0;
        $sumaFuncionalPonderada = 0;
        $sumaComportamentalPonderada = 0;
        $minFechaInicio = null;
        $maxFechaFin = null;

        foreach ($evaluacionesPrevias as $ep) {
            $dias = $ep->diasEvaluados();
            if ($dias <= 0) {
                continue;
            }

            $totalDias += $dias;
            $sumaFuncionalPonderada += ($ep->puntaje_funcional_obtenido * $dias);
            $sumaComportamentalPonderada += ($ep->puntaje_comportamental_obtenido * $dias);

            if (! $minFechaInicio || ($ep->periodo_evaluado_inicio && $ep->periodo_evaluado_inicio < $minFechaInicio)) {
                $minFechaInicio = $ep->periodo_evaluado_inicio;
            }
            if (! $maxFechaFin || ($ep->periodo_evaluado_fin && $ep->periodo_evaluado_fin > $maxFechaFin)) {
                $maxFechaFin = $ep->periodo_evaluado_fin;
            }
        }

        if ($totalDias <= 0) {
            return null;
        }

        $puntajeFuncional = $sumaFuncionalPonderada / $totalDias;
        $puntajeComportamental = $sumaComportamentalPonderada / $totalDias;

        $consolidacion = Evaluacion::where('concertacion_id', $concertacion->id)
            ->where('causal', 'Consolidación semestral')
            ->first();

        if ($consolidacion) {
            $consolidacion->update([
                'puntaje_funcional_obtenido' => round($puntajeFuncional, 2),
                'puntaje_comportamental_obtenido' => round($puntajeComportamental, 2),
                'fecha_evaluacion' => now(),
                'periodo_evaluado_inicio' => $minFechaInicio,
                'periodo_evaluado_fin' => $maxFechaFin,
                'activo' => true,
            ]);
        } else {
            $consolidacion = Evaluacion::create([
                'concertacion_id' => $concertacion->id,
                'causal' => 'Consolidación semestral',
                'estado' => 'calificada',
                'puntaje_funcional_obtenido' => round($puntajeFuncional, 2),
                'puntaje_comportamental_obtenido' => round($puntajeComportamental, 2),
                'fecha_evaluacion' => now(),
                'periodo_evaluado_inicio' => $minFechaInicio,
                'periodo_evaluado_fin' => $maxFechaFin,
                'activo' => true,
            ]);
        }

        return $consolidacion;
    }
}
