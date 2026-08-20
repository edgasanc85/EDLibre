<div>
    <div class="container-fluid px-4 py-4">
        <h1 class="h3 mb-4 text-dark fw-bold">Mis Evaluados</h1>

        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(!$evaluador)
            <div class="alert alert-warning">
                No tienes un perfil de Evaluador asignado en el sistema.
            </div>
        @else
            <div class="card shadow-sm mb-4">
                <div class="card-body bg-light border-bottom border-primary border-3 rounded-top text-primary">
                    <h5 class="mb-0"><strong>Dependencia a cargo:</strong> {{ $evaluador->dependencia->nombre ?? 'N/A' }}</h5>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-white"><i class="bi bi-people me-2 text-primary"></i> Listado de Servidores Públicos a Evaluar</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4 text-muted fw-bold">Nombre</th>
                                    <th class="py-3 px-4 text-muted fw-bold">Documento</th>
                                    <th class="py-3 px-4 text-muted fw-bold">Cargo</th>
                                    <th class="py-3 px-4 text-muted fw-bold">Nivel</th>
                                    <th class="py-3 px-4 text-muted fw-bold text-end">Estado y Acciones (Por Periodo Activo)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluados as $evaluado)
                                    <tr>
                                        <td class="py-3 px-4 align-middle">{{ $evaluado->user->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 align-middle">{{ $evaluado->user->numero_documento ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 align-middle">{{ $evaluado->cargo }}</td>
                                        <td class="py-3 px-4 align-middle"><span class="badge bg-secondary">{{ $evaluado->nivel->nombre ?? 'N/A' }}</span></td>
                                        <td class="py-3 px-4 align-middle text-end">
                                            @foreach($periodos as $periodo)
                                                @php
                                                    $concertacion = $evaluado->concertaciones->where('periodo_id', $periodo->id)->first();
                                                    $evalDefinitiva = $concertacion ? $concertacion->evaluaciones->whereIn('causal', ['Consolidación definitiva', 'Consolidación Definitiva'])->first() : null;
                                                @endphp
                                                <div class="d-inline-flex flex-wrap align-items-center gap-1 mb-2">
                                                    @if($concertacion && $concertacion->estado === 'en_revision')
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning" title="El evaluado envió los compromisos para revisión">
                                                            <i class="bi bi-clock-history me-1"></i> Pendiente Aprobación
                                                        </span>
                                                        <button wire:click="approveConcertacion({{ $concertacion->id }})" class="btn btn-sm btn-success rounded-pill shadow-sm" onclick="confirm('¿Estás seguro de APROBAR los compromisos de esta concertación?') || event.stopImmediatePropagation()" title="Aprobar Concertación">
                                                            <i class="bi bi-check-circle me-1"></i> Aprobar
                                                        </button>
                                                    @elseif($concertacion && $concertacion->estado === 'aprobado')
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success" title="Concertación aprobada por el evaluador">
                                                            <i class="bi bi-check-circle me-1"></i> Concertación Aprobada
                                                        </span>
                                                    @elseif($concertacion && $concertacion->estado === 'borrador')
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border" title="El evaluado aún no ha enviado los compromisos">
                                                            <i class="bi bi-pencil me-1"></i> En Borrador
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-muted border" title="Sin concertación iniciada">
                                                            <i class="bi bi-dash-circle me-1"></i> Sin Concertar
                                                        </span>
                                                    @endif

                                                    <a href="{{ route('concertacion', ['evaluado_id' => $evaluado->id, 'periodo_id' => $periodo->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm" title="Revisar formulario de concertación del periodo {{ $periodo->vigencia }}">
                                                        <i class="bi bi-file-check me-1"></i> Revisar {{ $periodo->vigencia }}
                                                    </a>

                                                    @if($concertacion && $concertacion->estado === 'aprobado')
                                                        <a href="{{ route('evaluaciones', $concertacion->id) }}" class="btn btn-sm btn-outline-warning rounded-pill shadow-sm" title="Ir a eventos de evaluación">
                                                            <i class="bi bi-bar-chart-steps me-1"></i> Evaluaciones
                                                        </a>
                                                        @if($evalDefinitiva && $evalDefinitiva->estado === 'aceptada')
                                                            <a href="{{ route('evaluacion.pdf', $evalDefinitiva->id) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill shadow-sm" title="Descargar PDF de la Evaluación Final Definitiva">
                                                                <i class="bi bi-file-pdf me-1"></i> PDF Final
                                                            </a>
                                                        @elseif($evalDefinitiva && $evalDefinitiva->estado === 'calificada')
                                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning" title="Evaluación final enviada, pendiente de aceptación por el evaluado">
                                                                <i class="bi bi-hourglass-split"></i> Final Notificada
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No hay servidores públicos registrados en esta dependencia.</td>
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
