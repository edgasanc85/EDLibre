<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evaluación del Desempeño Laboral</title>
    <style>
        body {
            font-family: 'Nunito Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background-color: #ffffff;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #334155;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
            color: #0f172a;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #475569;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ffffff;
        }
        th, td {
            border: 1px solid #94a3b8;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #334155;
        }
        .section-title {
            background-color: #f8fafc;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            padding: 6px;
            margin-bottom: 10px;
            text-transform: uppercase;
            border: 1px solid #94a3b8;
        }
        .signatures {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #0f172a;
            margin-top: 40px;
            padding-top: 5px;
        }
        .signature-line span.fw-bold {
            color: #0f172a;
        }
        .fw-bold { font-weight: bold; }
        .text-center { text-align: center; }
        .bg-light { background-color: #f8fafc; }
        
        .score-box {
            border: 2px solid #334155;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .score-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .score-value {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
        }
        .score-level {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .signatures {
            margin-top: 50px;
            width: 100%;
            page-break-inside: avoid;
        }
        @page {
            margin: 40px 40px 80px 40px;
        }

    </style>
</head>
<body>



    <main>

    <div class="header">
        <h1>Evaluación del Desempeño Laboral</h1>
        <p>Sistema de Evaluación del Desempeño Laboral - {{ $nombreEntidad }}</p>
    </div>

    <!-- DATOS GENERALES -->
    <div class="section-title">Datos Generales</div>
    <table>
        <tr>
            <th style="width: 20%">Periodo a Evaluar</th>
            <td style="width: 30%">{{ $evaluacion->concertacion->periodo->vigencia }}</td>
            <th style="width: 20%">Fechas Periodo</th>
            <td style="width: 30%">
                @php
                    $fechaInicio = $evaluacion->concertacion->evaluado->fecha_ingreso && $evaluacion->concertacion->evaluado->fecha_ingreso->gt($evaluacion->concertacion->periodo->fecha_inicio) 
                        ? $evaluacion->concertacion->evaluado->fecha_ingreso 
                        : $evaluacion->concertacion->periodo->fecha_inicio;
                        
                    $fechaFin = $evaluacion->concertacion->evaluado->fecha_retiro && $evaluacion->concertacion->evaluado->fecha_retiro->lt($evaluacion->concertacion->periodo->fecha_fin) 
                        ? $evaluacion->concertacion->evaluado->fecha_retiro 
                        : $evaluacion->concertacion->periodo->fecha_fin;
                @endphp
                {{ $fechaInicio->format('d/m/Y') }} al {{ $fechaFin->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <th>Causal Evaluación</th>
            <td colspan="3" class="fw-bold">{{ mb_strtoupper(str_replace('_', ' ', $evaluacion->causal)) }}</td>
        </tr>
    </table>

    <!-- DATOS DEL EVALUADO -->
    <div class="section-title">Datos del Servidor Público Evaluado</div>
    <table>
        <tr>
            <th style="width: 20%">Nombres y Apellidos</th>
            <td colspan="3">{{ $evaluacion->concertacion->evaluado->user->name }} {{ $evaluacion->concertacion->evaluado->user->last_name }}</td>
        </tr>
        <tr>
            <th style="width: 20%">Documento</th>
            <td style="width: 30%">{{ $evaluacion->concertacion->evaluado->user->numero_documento }}</td>
            <th style="width: 20%">Nivel Jerárquico</th>
            <td style="width: 30%">{{ $evaluacion->concertacion->evaluado->nivel->nombre ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th style="width: 20%">Cargo</th>
            <td style="width: 30%">{{ $evaluacion->concertacion->evaluado->cargo }}</td>
            <th style="width: 20%">Dependencia</th>
            <td style="width: 30%">{{ $evaluacion->concertacion->evaluado->dependencia->nombre ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- DATOS DEL EVALUADOR -->
    <div class="section-title">Datos del Evaluador</div>
    <table>
        <tr>
            <th style="width: 20%">Nombres y Apellidos</th>
            <td style="width: 30%">{{ $evaluador->user->name ?? 'N/A' }} {{ $evaluador->user->last_name ?? '' }}</td>
            <th style="width: 20%">Cargo</th>
            <td style="width: 30%">{{ $evaluador->cargo ?? 'N/A' }}</td>
        </tr>
    </table>
    
    <!-- RESULTADOS CONSOLIDADOS -->
    <div class="score-box">
        <div class="score-title">Calificación Definitiva</div>
        <div class="score-value">{{ number_format($totalEvaluacion, 2) }} / 100.00</div>
        <div class="score-level">NIVEL: {{ $nivelDestacado }}</div>
        <div style="margin-top: 10px; font-size: 11px;">
            (Funcional: {{ number_format($evaluacion->puntaje_funcional_obtenido, 2) }} / 85.00 | Comportamental: {{ number_format($evaluacion->puntaje_comportamental_obtenido, 2) }} / 15.00)
        </div>
    </div>

    <!-- COMPROMISOS FUNCIONALES -->
    <div class="section-title">Resultados Compromisos Funcionales (85%)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 65%">Compromiso</th>
                <th style="width: 15%" class="text-center">Peso (%)</th>
                <th style="width: 15%" class="text-center">Calificación (0-100)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($evaluacion->evaluacionCompromisos as $index => $ec)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $ec->compromisoFuncional->verbo }} 
                        {{ $ec->compromisoFuncional->objeto }} 
                        {{ $ec->compromisoFuncional->condicion }}
                    </td>
                    <td class="text-center">{{ $ec->compromisoFuncional->peso }}%</td>
                    <td class="text-center fw-bold">{{ number_format($ec->calificacion, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-center fw-bold bg-light">PUNTAJE OBTENIDO (Ponderado al 85%)</td>
                <td class="text-center fw-bold bg-light">{{ number_format($evaluacion->puntaje_funcional_obtenido, 2) }} / 85.00</td>
            </tr>
        </tbody>
    </table>

    <!-- COMPROMISOS COMPORTAMENTALES -->
    <div class="section-title">Resultados Competencias Comportamentales (15%)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 25%">Competencia</th>
                <th style="width: 60%">Conductas Evaluadas</th>
                <th style="width: 15%" class="text-center">Promedio (0-100)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comportamentalesAgrupadas as $datos)
                <tr>
                    <td>
                        <span class="fw-bold">{{ $datos['competencia'] }}</span><br>
                        <span style="font-size: 9px; color: #475569;">{{ $datos['definicion'] }}</span>
                    </td>
                    <td>
                        <ul style="margin: 0; padding-left: 15px; font-size: 10px;">
                            @foreach($datos['calificaciones'] as $cal)
                                <li>{{ $cal->conducta->descripcion }} <span class="fw-bold">({{ number_format($cal->calificacion, 2) }})</span></li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-center fw-bold align-middle">{{ number_format($datos['promedio'], 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-center fw-bold bg-light">PUNTAJE OBTENIDO (Ponderado al 15%)</td>
                <td class="text-center fw-bold bg-light">{{ number_format($evaluacion->puntaje_comportamental_obtenido, 2) }} / 15.00</td>
            </tr>
        </tbody>
    </table>

    <!-- FIRMAS -->
    <div class="signatures">
        <div class="signature-box" style="float: left;">
            <div class="signature-line">
                <span class="fw-bold">{{ $evaluacion->concertacion->evaluado->user->name }} {{ $evaluacion->concertacion->evaluado->user->last_name }}</span><br>
                <span>Evaluado</span><br>
                <span style="font-size: 9px; color: #475569;">Aceptado: {{ $evaluacion->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        
        <div class="signature-box" style="float: right;">
            <div class="signature-line">
                @if($evaluador)
                    <span class="fw-bold">{{ $evaluador->user->name }} {{ $evaluador->user->last_name }}</span><br>
                    <span>Evaluador</span><br>
                    <span style="font-size: 9px; color: #475569;">Evaluado: {{ $evaluacion->fecha_evaluacion ? $evaluacion->fecha_evaluacion->format('d/m/Y H:i') : 'N/A' }}</span>
                @else
                    <span class="fw-bold">No Asignado</span><br>
                    <span>Evaluador</span>
                @endif
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
    
    </main>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $text = "Documento generado con la tecnología de EDGASANC.COM el {{ now()->format("d/m/Y H:i") }} - Página " . $PAGE_NUM . " de " . $PAGE_COUNT;
                $font = $fontMetrics->get_font("sans-serif", "normal");
                $size = 9;
                $width = $fontMetrics->get_text_width($text, $font, $size);
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 35;
                $pdf->text($x, $y, $text, $font, $size, array(71/255, 85/255, 105/255));
            ');
        }
    </script>
</body>
</html>
