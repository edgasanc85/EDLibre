<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;
use App\Models\Evaluador;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluacionPdfController extends Controller
{
    public function export($id)
    {
        $evaluacion = Evaluacion::with([
            'concertacion.evaluado.user',
            'concertacion.evaluado.dependencia',
            'concertacion.evaluado.nivel',
            'concertacion.evaluador.user',
            'concertacion.periodo',
            'evaluacionCompromisos.compromisoFuncional',
            'evaluacionComportamentales.compromisoComportamental.competencia',
            'evaluacionComportamentales.conducta'
        ])->findOrFail($id);

        if ($evaluacion->estado !== 'aceptada') {
            abort(403, 'El reporte PDF solo está disponible para evaluaciones en estado ACEPTADA.');
        }

        $user_id = auth()->id();
        $isEvaluado = $evaluacion->concertacion->evaluado->user_id === $user_id;
        
        $isEvaluador = Evaluador::where('user_id', $user_id)
            ->where('dependencia_id', $evaluacion->concertacion->evaluado->dependencia_id)
            ->active()
            ->exists();

        if (!$isEvaluado && !$isEvaluador && !auth()->user()->is_admin) {
            abort(403, 'No tienes permisos para ver esta evaluación.');
        }

        $evaluador = $evaluacion->concertacion->evaluador ?? Evaluador::with('user')->where('dependencia_id', $evaluacion->concertacion->evaluado->dependencia_id)->active()->first();

        $entidadRaiz = \App\Models\Dependencia::whereNull('parent_id')->first();
        $nombreEntidad = $entidadRaiz ? $entidadRaiz->nombre : 'Sistema de Evaluación del Desempeño Laboral';

        // Agrupar calificaciones comportamentales por competencia
        $comportamentalesAgrupadas = [];
        foreach ($evaluacion->evaluacionComportamentales as $ec) {
            $comp_id = $ec->compromisoComportamental->competencia_id;
            if (!isset($comportamentalesAgrupadas[$comp_id])) {
                $comportamentalesAgrupadas[$comp_id] = [
                    'competencia' => $ec->compromisoComportamental->competencia->nombre,
                    'definicion' => $ec->compromisoComportamental->competencia->definicion,
                    'calificaciones' => [],
                    'promedio' => 0,
                ];
            }
            $comportamentalesAgrupadas[$comp_id]['calificaciones'][] = $ec;
        }

        foreach ($comportamentalesAgrupadas as $comp_id => &$datos) {
            $suma = 0;
            foreach ($datos['calificaciones'] as $cal) {
                $suma += $cal->calificacion;
            }
            if (count($datos['calificaciones']) > 0) {
                $datos['promedio'] = $suma / count($datos['calificaciones']);
            }
        }
        
        $totalEvaluacion = $evaluacion->puntaje_funcional_obtenido + $evaluacion->puntaje_comportamental_obtenido;
        $nivelDestacado = 'No Aprobatorio';
        if ($totalEvaluacion >= 90) {
            $nivelDestacado = 'Sobresaliente';
        } elseif ($totalEvaluacion >= 65) {
            $nivelDestacado = 'Satisfactorio';
        }

        $pdf = Pdf::setOption('isPhpEnabled', true)
            ->loadView('pdf.evaluacion', compact('evaluacion', 'evaluador', 'nombreEntidad', 'comportamentalesAgrupadas', 'totalEvaluacion', 'nivelDestacado'))
            ->setPaper('legal', 'portrait');
            
        return $pdf->download('evaluacion_' . $evaluacion->concertacion->evaluado->user->numero_documento . '_' . $evaluacion->concertacion->periodo->vigencia . '.pdf');
    }
}
