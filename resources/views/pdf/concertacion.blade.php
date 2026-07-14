<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Concertación de Compromisos Laborales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .section-title {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #aaa;
            text-transform: uppercase;
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
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
        .fw-bold { font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

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
                {{ $concertacion->periodo->fecha_inicio->format('d/m/Y') }} al {{ $concertacion->periodo->fecha_fin->format('d/m/Y') }}
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
                <span style="font-size: 9px; color: #666;">Aprobado: {{ $concertacion->fecha_aprobacion_evaluado ? $concertacion->fecha_aprobacion_evaluado->format('d/m/Y H:i') : 'N/A' }}</span>
            </div>
        </div>

        <div class="signature-box" style="float: right;">
            <div class="signature-line">
                <span class="fw-bold">Firma del Evaluador</span><br>
                {{ $evaluador->user->name ?? 'N/A' }}<br>
                <span style="font-size: 9px; color: #666;">Aprobado: {{ $concertacion->fecha_aprobacion_evaluador ? $concertacion->fecha_aprobacion_evaluador->format('d/m/Y H:i') : 'N/A' }}</span>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
    
    <div style="margin-top: 30px; text-align: center; font-size: 9px; color: #888;">
        Documento generado por EDGASANC.COM el {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
