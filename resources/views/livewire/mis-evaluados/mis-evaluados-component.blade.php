<div>
    <div class="container-fluid px-4 py-4">
        <h1 class="h3 mb-4 text-dark fw-bold">Mis Evaluados</h1>

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
                                    <th class="py-3 px-4 text-muted fw-bold text-end">Acciones (Por Periodo Activo)</th>
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
                                                @endphp
                                                <a href="{{ route('concertacion', ['evaluado_id' => $evaluado->id, 'periodo_id' => $periodo->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill mb-1 shadow-sm" title="Periodo {{ $periodo->vigencia }}">
                                                    <i class="bi bi-file-check me-1"></i> Revisar {{ $periodo->vigencia }}
                                                </a>
                                                @if($concertacion && $concertacion->estado === 'aprobado')
                                                    <a href="{{ route('evaluaciones', $concertacion->id) }}" class="btn btn-sm btn-outline-warning rounded-pill mb-1 shadow-sm">
                                                        <i class="bi bi-bar-chart-steps me-1"></i> Calificar
                                                    </a>
                                                @endif
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
