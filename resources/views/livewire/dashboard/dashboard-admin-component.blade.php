<div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="form-label text-muted fw-bold small">Seleccione la Vigencia (Periodo):</label>
            <select wire:model.live="selectedPeriodoId" class="form-select shadow-sm">
                @foreach($periodos as $periodo)
                    <option value="{{ $periodo->id }}">{{ $periodo->vigencia }} {{ $periodo->activo ? '(Activo)' : '' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-8">
            <label class="form-label text-muted fw-bold small">Búsqueda rápida:</label>
            <div class="position-relative">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control shadow-sm ps-4" placeholder="Buscar funcionario por nombre o documento...">
                <i class="bi bi-search position-absolute text-muted" style="top: 50%; left: 10px; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>

    @if($dependenciasAgrupadas->isEmpty())
        <div class="text-center py-5 bg-white rounded shadow-sm border border-light">
            <i class="bi bi-folder-x display-4 text-muted mb-3 d-block"></i>
            <h5 class="text-secondary fw-bold">No hay registros</h5>
            <p class="text-muted">No se encontraron concertaciones para el periodo y/o búsqueda seleccionada.</p>
        </div>
    @else
        <div class="accordion shadow-sm" id="accordionDependencias">
            @foreach($dependenciasAgrupadas as $nombreDependencia => $concertaciones)
                <div class="accordion-item border-0 mb-3 bg-white rounded-3 shadow-sm overflow-hidden">
                    <h2 class="accordion-header" id="heading-{{ \Illuminate\Support\Str::slug($nombreDependencia) }}">
                        <button class="accordion-button collapsed fw-bold py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ \Illuminate\Support\Str::slug($nombreDependencia) }}" aria-expanded="false" aria-controls="collapse-{{ \Illuminate\Support\Str::slug($nombreDependencia) }}">
                            <div class="d-flex w-100 justify-content-between align-items-center me-3">
                                <div>
                                    <i class="bi bi-diagram-3 me-2" style="color: var(--ea-primary);"></i>
                                    {{ $nombreDependencia }}
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $concertaciones->count() }} evaluados</span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse-{{ \Illuminate\Support\Str::slug($nombreDependencia) }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ \Illuminate\Support\Str::slug($nombreDependencia) }}" data-bs-parent="#accordionDependencias">
                        <div class="accordion-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 text-muted fw-bold small text-uppercase">Evaluado</th>
                                            <th class="px-4 py-3 text-muted fw-bold small text-uppercase">Evaluador</th>
                                            <th class="px-4 py-3 text-muted fw-bold small text-uppercase text-center">Concertación</th>
                                            <th class="px-4 py-3 text-muted fw-bold small text-uppercase text-center">Evaluación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($concertaciones as $concertacion)
                                            @php
                                                $evaluadoUser = $concertacion->evaluado->user ?? null;
                                                $evaluadorUser = $concertacion->evaluador->user ?? null;
                                                $evaluacion = $concertacion->evaluaciones->first();
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="fw-bold text-dark">{{ $evaluadoUser->name ?? 'N/A' }}</div>
                                                    <div class="small text-muted"><i class="bi bi-person-badge me-1"></i>{{ $evaluadoUser->numero_documento ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-dark">{{ $evaluadorUser->name ?? 'Sin asignar' }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if(strtolower($concertacion->estado) == 'aprobado')
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 mb-1 d-inline-block">Aprobado</span>
                                                        <div>
                                                            <a href="{{ route('concertacion.pdf', $concertacion->id) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Ver Acta PDF">
                                                                <i class="bi bi-file-earmark-pdf"></i> PDF
                                                            </a>
                                                        </div>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">{{ ucfirst($concertacion->estado) }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if($evaluacion)
                                                        @if(strtolower($evaluacion->estado) == 'aceptada')
                                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 mb-1 d-inline-block">Aceptada</span>
                                                            <div>
                                                                <a href="{{ route('evaluacion.pdf', $evaluacion->id) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Ver Reporte PDF">
                                                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                                                </a>
                                                            </div>
                                                        @else
                                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">{{ ucfirst($evaluacion->estado) }}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-light text-muted border px-2 py-1">Sin evaluar</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
