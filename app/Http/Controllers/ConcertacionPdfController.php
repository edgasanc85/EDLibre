<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concertacion;
use App\Models\Evaluador;
use Barryvdh\DomPDF\Facade\Pdf;

class ConcertacionPdfController extends Controller
{
    public function export($id)
    {
        $concertacion = Concertacion::with([
            'evaluado.user',
            'evaluado.dependencia',
            'evaluado.nivel',
            'evaluador.user',
            'periodo',
            'compromisosFuncionals',
            'compromisosComportamentals.competencia',
            'compromisosComportamentals.conductas'
        ])->findOrFail($id);

        if ($concertacion->estado !== 'aprobado') {
            abort(403, 'El documento PDF solo está disponible para concertaciones aprobadas.');
        }

        $user_id = auth()->id();
        $isEvaluado = $concertacion->evaluado->user_id === $user_id;
        
        $isEvaluador = Evaluador::where('user_id', $user_id)
            ->where('dependencia_id', $concertacion->evaluado->dependencia_id)
            ->active()
            ->exists();

        if (!$isEvaluado && !$isEvaluador && !auth()->user()->is_admin) {
            abort(403, 'No tienes permisos para ver esta concertación.');
        }

        // Obtener el evaluador activo de la dependencia (ya que evaluador_id en concertaciones puede ser nulo)
        $evaluador = $concertacion->evaluador ?? Evaluador::with('user')->where('dependencia_id', $concertacion->evaluado->dependencia_id)->active()->first();

        $entidadRaiz = \App\Models\Dependencia::whereNull('parent_id')->first();
        $nombreEntidad = $entidadRaiz ? $entidadRaiz->nombre : 'Sistema de Evaluación del Desempeño Laboral';

        $pdf = Pdf::setOption('isPhpEnabled', true)
            ->loadView('pdf.concertacion', compact('concertacion', 'evaluador', 'nombreEntidad'))
            ->setPaper('legal', 'portrait');
            
        return $pdf->download('concertacion_' . $concertacion->evaluado->user->numero_documento . '_' . $concertacion->periodo->vigencia . '.pdf');
    }
}
