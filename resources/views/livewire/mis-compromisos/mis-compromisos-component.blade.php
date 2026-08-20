<div>
    <div class="container-fluid px-4 py-4">
        <h1 class="h3 mb-4 text-dark fw-bold">Mis Compromisos</h1>

        @if(!$evaluado)
            <div class="alert alert-warning">
                No tienes un perfil de Evaluado asignado en el sistema.
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-calendar-event me-2 text-primary"></i> Periodos de Evaluación</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4 text-muted fw-bold">Vigencia</th>
                                    <th class="py-3 px-4 text-muted fw-bold">Fecha Inicio</th>
                                    <th class="py-3 px-4 text-muted fw-bold">Fecha Fin</th>
                                    <th class="py-3 px-4 text-muted fw-bold text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($periodos as $periodo)
                                    <tr>
                                        <td class="py-3 px-4 align-middle">
                                            <span class="fw-bold">{{ $periodo->vigencia }}</span>
                                            @php
                                                $concertacion = $evaluado->concertaciones->where('periodo_id', $periodo->id)->first();
                                                $evalDefinitiva = $concertacion ? $concertacion->evaluaciones->whereIn('causal', ['Consolidación definitiva', 'Consolidación Definitiva'])->first() : null;
                                            @endphp
                                            @if($evalDefinitiva && $evalDefinitiva->estado === 'aceptada')
                                                <br><span class="badge bg-success bg-opacity-10 text-success border border-success mt-1"><i class="bi bi-trophy-fill me-1"></i> Calificación Final: {{ number_format($evalDefinitiva->puntaje_funcional_obtenido + $evalDefinitiva->puntaje_comportamental_obtenido, 2) }}</span>
                                            @elseif($evalDefinitiva && $evalDefinitiva->estado === 'calificada')
                                                <br><span class="badge bg-warning bg-opacity-10 text-warning border border-warning mt-1"><i class="bi bi-bell-fill me-1"></i> Evaluación Final Pendiente Aceptar</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 align-middle">{{ $periodo->fecha_inicio->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4 align-middle">{{ $periodo->fecha_fin->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4 align-middle text-end">
                                            <a href="{{ route('concertacion', ['evaluado_id' => $evaluado->id, 'periodo_id' => $periodo->id]) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm mb-1">
                                                <i class="bi bi-pencil-square me-1"></i> Concertar / Ver
                                            </a>
                                            @if($concertacion && $concertacion->estado === 'aprobado')
                                                <a href="{{ route('evidencias', $concertacion->id) }}" class="btn btn-sm btn-info text-dark rounded-pill px-3 shadow-sm mb-1 ms-1">
                                                    <i class="bi bi-camera me-1"></i> Evidencias
                                                </a>
                                                <a href="{{ route('evaluaciones', $concertacion->id) }}" class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm mb-1 ms-1">
                                                    <i class="bi bi-bar-chart-steps me-1"></i> Evaluaciones
                                                </a>
                                                @if($evalDefinitiva && $evalDefinitiva->estado === 'aceptada')
                                                    <a href="{{ route('evaluacion.pdf', $evalDefinitiva->id) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm mb-1 ms-1" title="Descargar Evaluación Final en PDF">
                                                        <i class="bi bi-file-pdf me-1"></i> PDF Final
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No hay periodos activos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
