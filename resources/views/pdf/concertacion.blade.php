<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Concertación de Compromisos Laborales</title>
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
            vertical-align: top;
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
            page-break-inside: avoid;
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
        
        @page {
            margin: 40px 40px 80px 40px;
        }

    </style>
</head>
<body>



    <main>

    <div class="header">
        <h1>Concertación de Compromisos Laborales</h1>
        <p>Sistema de Evaluación del Desempeño Laboral - {{ $nombreEntidad }}</p>
    </div>

    <!-- DATOS GENERALES -->
    <div class="section-title">Datos Generales</div>
    <table>
        <tr>
            <th style="width: 20%">Periodo a Evaluar</th>
            <td style="width: 30%">{{ $concertacion->periodo->vigencia }}</td>
            <th style="width: 20%">Fechas Periodo</th>
            <td style="width: 30%">
                @php
                    $fechaInicio = $concertacion->evaluado->fecha_ingreso && $concertacion->evaluado->fecha_ingreso->gt($concertacion->periodo->fecha_inicio) 
                        ? $concertacion->evaluado->fecha_ingreso 
                        : $concertacion->periodo->fecha_inicio;
                        
                    $fechaFin = $concertacion->evaluado->fecha_retiro && $concertacion->evaluado->fecha_retiro->lt($concertacion->periodo->fecha_fin) 
                        ? $concertacion->evaluado->fecha_retiro 
                        : $concertacion->periodo->fecha_fin;
                @endphp
                {{ $fechaInicio->format('d/m/Y') }} al {{ $fechaFin->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <!-- DATOS DEL EVALUADO -->
    <div class="section-title">Datos del Servidor Público Evaluado</div>
    <table>
        <tr>
            <th style="width: 20%">Nombres y Apellidos</th>
            <td colspan="3">{{ $concertacion->evaluado->user->name }}</td>
        </tr>
        <tr>
            <th style="width: 20%">Documento</th>
            <td style="width: 30%">{{ $concertacion->evaluado->user->numero_documento }}</td>
            <th style="width: 20%">Nivel Jerárquico</th>
            <td style="width: 30%">{{ $concertacion->evaluado->nivel->nombre ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th style="width: 20%">Cargo</th>
            <td style="width: 30%">{{ $concertacion->evaluado->cargo }}</td>
            <th style="width: 20%">Dependencia</th>
            <td style="width: 30%">{{ $concertacion->evaluado->dependencia->nombre ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- DATOS DEL EVALUADOR -->
    <div class="section-title">Datos del Evaluador</div>
    <table>
        <tr>
            <th style="width: 20%">Nombres y Apellidos</th>
            <td style="width: 30%">{{ $evaluador->user->name ?? 'N/A' }}</td>
            <th style="width: 20%">Cargo</th>
            <td style="width: 30%">{{ $evaluador->cargo ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- COMPROMISOS FUNCIONALES -->
    <div class="section-title">Compromisos Funcionales (85%)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">#</th>
                <th style="width: 20%">Verbo</th>
                <th style="width: 35%">Objeto</th>
                <th style="width: 30%">Condición</th>
                <th style="width: 10%" class="text-center">Peso (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($concertacion->compromisosFuncionals as $index => $cf)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $cf->verbo }}</td>
                <td>{{ $cf->objeto }}</td>
                <td>{{ $cf->condicion }}</td>
                <td class="text-center">{{ $cf->peso }}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right">Total Peso:</th>
                <th class="text-center">{{ $concertacion->compromisosFuncionals->sum('peso') }}%</th>
            </tr>
        </tfoot>
    </table>

    <!-- COMPROMISOS COMPORTAMENTALES -->
    <div class="section-title">Compromisos Comportamentales (15%)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">#</th>
                <th style="width: 30%">Competencia</th>
                <th style="width: 65%">Conductas Asociadas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($concertacion->compromisosComportamentals as $index => $cc)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $cc->competencia->nombre }}</td>
                <td>
                    <ul style="margin: 0; padding-left: 15px;">
                        @foreach($cc->conductas as $conducta)
                            <li>{{ $conducta->descripcion }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FIRMAS -->
    <div class="signatures">
        <div class="signature-box" style="float: left;">
            <div class="signature-line">
                <span class="fw-bold">Firma del Servidor Evaluado</span><br>
                {{ $concertacion->evaluado->user->name }}<br>
                <span style="font-size: 9px; color: #475569;">Aprobado: {{ $concertacion->fecha_aprobacion_evaluado ? $concertacion->fecha_aprobacion_evaluado->format('d/m/Y H:i') : 'N/A' }}</span>
            </div>
        </div>

        <div class="signature-box" style="float: right;">
            <div class="signature-line">
                <span class="fw-bold">Firma del Evaluador</span><br>
                {{ $evaluador->user->name ?? 'N/A' }}<br>
                <span style="font-size: 9px; color: #475569;">Aprobado: {{ $concertacion->fecha_aprobacion_evaluador ? $concertacion->fecha_aprobacion_evaluador->format('d/m/Y H:i') : 'N/A' }}</span>
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
