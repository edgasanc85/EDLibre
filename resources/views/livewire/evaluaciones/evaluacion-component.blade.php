<div>
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-dark fw-bold mb-0">Eventos de Evaluación</h1>
            <a href="{{ $rolActual == 'evaluado' ? route('mis-compromisos') : route('mis-evaluados') }}" class="btn btn-outline-secondary rounded-pill shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body bg-light border-bottom border-warning border-3 rounded-top d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 text-warning fw-bold"><i class="bi bi-clipboard-data me-2"></i> Evaluaciones del Periodo {{ $concertacion->periodo->vigencia }}</h5>
                    <p class="mb-0 text-muted">Evaluado: {{ $concertacion->evaluado->user->name }} - {{ $concertacion->evaluado->user->numero_documento }}</p>
                </div>
                @if($rolActual == 'evaluador')
                    <button wire:click="$set('showCreateModal', true)" class="btn btn-warning rounded-pill shadow-sm fw-bold">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Evaluación
                    </button>
                @endif
            </div>
        </div>

        <!-- Lista de Evaluaciones -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4">Fecha</th>
                                <th class="py-3 px-4">Causal de Evaluación</th>
                                <th class="py-3 px-4">Puntajes (Funcional / Comportamental)</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Estado</th>
                                <th class="py-3 px-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($concertacion->evaluaciones as $eval)
                                <tr>
                                    <td class="py-3 px-4 align-middle">{{ $eval->fecha_evaluacion ? $eval->fecha_evaluacion->format('d/m/Y') : 'Borrador' }}</td>
                                    <td class="py-3 px-4 align-middle fw-semibold">{{ $eval->causal }}</td>
                                    <td class="py-3 px-4 align-middle">
                                        @if($eval->puntaje_funcional_obtenido !== null)
                                            <span class="badge bg-success bg-opacity-10 text-success fs-6">{{ $eval->puntaje_funcional_obtenido }} / 85</span>
                                            <span class="badge bg-info bg-opacity-10 text-info fs-6">{{ $eval->puntaje_comportamental_obtenido ?? 0 }} / 15</span>
                                        @else
                                            <span class="text-muted small">Pendiente</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 align-middle fw-bold">
                                        @if($eval->puntaje_funcional_obtenido !== null)
                                            {{ $eval->puntaje_funcional_obtenido + ($eval->puntaje_comportamental_obtenido ?? 0) }} / 100
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        @if($eval->estado == 'en_revision') <span class="badge bg-secondary">Borrador Evaluador</span>
                                        @elseif($eval->estado == 'calificada') <span class="badge bg-primary">Enviada a Evaluado</span>
                                        @elseif($eval->estado == 'aceptada') <span class="badge bg-success">Aceptada</span>
                                        @elseif($eval->estado == 'rechazada_comision') <span class="badge bg-danger">En Comisión</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        @if($rolActual == 'evaluador' && in_array($eval->estado, ['en_revision']))
                                            <button wire:click="openGradeModal({{ $eval->id }})" class="btn btn-sm btn-primary rounded-pill px-3">
                                                <i class="bi bi-pencil-square"></i> Calificar
                                            </button>
                                        @endif
                                        @if($rolActual == 'evaluado' && $eval->estado == 'calificada')
                                            <button wire:click="acceptEvaluacion({{ $eval->id }})" class="btn btn-sm btn-success rounded-pill px-3" onclick="confirm('¿Estás seguro de ACEPTAR esta calificación?') || event.stopImmediatePropagation()">
                                                <i class="bi bi-check-circle"></i> Aceptar
                                            </button>
                                        @endif
                                        @if($eval->estado == 'aceptada')
                                            <a href="{{ route('evaluacion.pdf', $eval->id) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Descargar Reporte PDF">
                                                <i class="bi bi-file-pdf"></i> PDF
                                            </a>
                                        @endif
                                        @if($eval->estado != 'en_revision')
                                            <!-- Ver detalle -->
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No hay evaluaciones registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Evaluación -->
    @if($showCreateModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i> Crear Nueva Evaluación</h5>
                    <button type="button" class="btn-close" wire:click="$set('showCreateModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Causal de Evaluación</label>
                        <select wire:model="causal" class="form-select">
                            <option value="">Seleccione una causal...</option>
                            <option value="Parcial primer semestre">Parcial primer semestre</option>
                            <option value="Parcial segundo semestre">Parcial segundo semestre</option>
                            <option value="Por cambio de evaluador">Por cambio de evaluador</option>
                            <option value="Por cambio definitivo del empleo">Por cambio definitivo del empleo</option>
                            <option value="Por separación temporal del empleo superior a 30 días">Por separación temporal del empleo superior a 30 días</option>
                            <option value="La que corresponda al lapso comprendido">La que corresponda al lapso comprendido...</option>
                            <option value="Consolidación semestral">Consolidación semestral</option>
                            <option value="Consolidación definitiva">Consolidación definitiva</option>
                        </select>
                        @error('causal') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" wire:click="$set('showCreateModal', false)">Cancelar</button>
                    <button type="button" class="btn btn-warning fw-bold" wire:click="createEvaluacion">Crear y Calificar</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Calificar Evaluación -->
    @if($showGradeModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.7);">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Calificar Compromisos</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showGradeModal', false)"></button>
                </div>
                <div class="modal-body bg-light">
                    @if(session()->has('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    @foreach($concertacion->compromisosFuncionals as $cf)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-dark">
                                <h6 class="mb-0 fw-bold">{{ $cf->verbo }} {{ $cf->objeto }} (Peso: {{ $cf->peso }}%)</h6>
                            </div>
                            <div class="card-body bg-white">
                                <div class="row">
                                    <div class="col-md-10">
                                        <p class="small fw-bold text-muted mb-2">Evidencias aportadas:</p>
                                        @if($cf->evidencias->where('activo', true)->count() > 0)
                                            <ul class="list-unstyled mb-0 small">
                                                @foreach($cf->evidencias->where('activo', true) as $ev)
                                                    <li class="mb-1">
                                                        <i class="bi bi-link-45deg text-primary"></i>
                                                        <a href="{{ $ev->ubicacion }}" target="_blank">{{ $ev->descripcion }}</a>
                                                        <span class="text-muted">({{ $ev->created_at->format('d/m/Y') }})</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-danger small fst-italic">Sin evidencias aportadas.</span>
                                        @endif
                                    </div>
                                    <div class="col-md-2 border-start">
                                        <label class="form-label small fw-bold">Calificación (0 a 100)</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" max="100" wire:model="calificaciones.{{ $cf->id }}" class="form-control" placeholder="0 - 100">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <hr class="my-4 text-primary">
                    <h5 class="fw-bold mb-3"><i class="bi bi-person-lines-fill me-2"></i> Compromisos Comportamentales (15%)</h5>
                    
                    @foreach($concertacion->compromisosComportamentals as $cc)
                        <div class="card mb-3 shadow-sm border-info">
                            <div class="card-header bg-opacity-10">
                                <h6 class="mb-0 fw-bold">{{ $cc->competencia->nombre }}</h6>
                            </div>
                            <div class="card-body bg-white">
                                <p class="small text-dark mb-3">{{ $cc->competencia->definicion }}</p>
                                
                                @foreach($cc->conductas as $conducta)
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-10">
                                            <p class="mb-0 text-dark small"><i class="bi bi-check2-square text-success me-1"></i> {{ $conducta->descripcion }}</p>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-sm">
                                                <input type="number" step="0.01" min="0" max="100" wire:model="calificaciones_comportamentales.{{ $cc->id }}_{{ $conducta->id }}" class="form-control" placeholder="0 - 100">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer border-0 bg-white">
                    <button type="button" class="btn btn-light" wire:click="$set('showGradeModal', false)">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="saveCalificaciones">Guardar Borrador</button>
                    <button type="button" class="btn btn-success fw-bold" wire:click="notificarEvaluacion" onclick="confirm('Al enviar al evaluado ya no podrás modificar estas calificaciones. ¿Proceder?') || event.stopImmediatePropagation()">
                        <i class="bi bi-send me-1"></i> Notificar a Evaluado
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
