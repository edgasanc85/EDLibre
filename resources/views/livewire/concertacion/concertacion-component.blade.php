<div>
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-dark fw-bold">Fijación de Compromisos</h1>
            <div>
                @if($concertacion)
                    <span class="badge {{ $concertacion->estado == 'aprobado' ? 'bg-success' : ($concertacion->estado == 'en_revision' ? 'bg-warning text-dark' : 'bg-secondary') }} px-3 py-2 rounded-pill">
                        Estado: {{ strtoupper(str_replace('_', ' ', $concertacion->estado)) }}
                    </span>
                @else
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">Estado: NUEVO (Borrador)</span>
                @endif
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @error('general')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @enderror

        <!-- Compromisos Funcionales -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-white"><i class="bi bi-list-check me-2" style="color: var(--ea-primary);"></i> Compromisos Funcionales 85% ({{ count($funcionales) }}/5)</h5>
                @if(!$isReadOnly)
                <button wire:click="addFuncional" class="btn btn-sm btn-primary rounded-pill" {{ count($funcionales) >= 5 ? 'disabled' : '' }}>
                    <i class="bi bi-plus-lg me-1"></i> Agregar Funcional
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4 text-muted fw-bold" style="width: 25%">Verbo</th>
                                <th class="py-3 px-4 text-muted fw-bold" style="width: 25%">Objeto</th>
                                <th class="py-3 px-4 text-muted fw-bold" style="width: 25%">Condición</th>
                                <th class="py-3 px-4 text-muted fw-bold" style="width: 15%">Peso (%)</th>
                                <th class="py-3 px-4 text-muted fw-bold text-end" style="width: 10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($funcionales as $index => $cf)
                                <tr>
                                    <td class="py-3 px-4">
                                        <input type="text" wire:model="funcionales.{{ $index }}.verbo" class="form-control form-control-sm @error('funcional.'.$index) is-invalid @enderror" placeholder="Ej: Elaborar" {{ $isReadOnly ? 'disabled' : '' }}>
                                    </td>
                                    <td class="py-3 px-4">
                                        <textarea wire:model="funcionales.{{ $index }}.objeto" class="form-control form-control-sm @error('funcional.'.$index) is-invalid @enderror" rows="2" placeholder="Ej: los informes de gestión" {{ $isReadOnly ? 'disabled' : '' }}></textarea>
                                    </td>
                                    <td class="py-3 px-4">
                                        <textarea wire:model="funcionales.{{ $index }}.condicion" class="form-control form-control-sm @error('funcional.'.$index) is-invalid @enderror" rows="2" placeholder="Ej: mensualmente según formato" {{ $isReadOnly ? 'disabled' : '' }}></textarea>
                                    </td>
                                    <td class="py-3 px-4">
                                        <input type="number" wire:model="funcionales.{{ $index }}.peso" class="form-control form-control-sm @error('funcional.'.$index) is-invalid @enderror" placeholder="0 - 100" {{ $isReadOnly ? 'disabled' : '' }}>
                                    </td>
                                    <td class="py-3 px-4 text-end">
                                        @if(!$isReadOnly)
                                        <button wire:click="removeFuncional({{ $index }})" class="btn btn-sm btn-icon btn-outline-danger border-0" title="Eliminar" {{ count($funcionales) <= 3 ? 'disabled' : '' }}>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Compromisos Comportamentales -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-white"><i class="bi bi-person-lines-fill me-2" style="color: var(--ea-primary);"></i> Compromisos Comportamentales 25% ({{ count($comportamentales) }}/5)</h5>
                @if(!$isReadOnly)
                <button wire:click="addComportamental" class="btn btn-sm btn-primary rounded-pill" {{ count($comportamentales) >= 5 ? 'disabled' : '' }}>
                    <i class="bi bi-plus-lg me-1"></i> Agregar Comportamental
                </button>
                @endif
            </div>
            <div class="card-body p-4">
                @foreach($comportamentales as $index => $cc)
                    <div class="bg-light p-3 rounded-3 mb-3 border border-secondary border-opacity-10 position-relative">
                        @if(!$isReadOnly)
                        <button wire:click="removeComportamental({{ $index }})" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 border-0" title="Eliminar" {{ count($comportamentales) <= 3 ? 'disabled' : '' }}>
                            <i class="bi bi-x-lg"></i>
                        </button>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small">Competencia</label>
                                <select wire:model.live="comportamentales.{{ $index }}.competencia_id" class="form-select form-select-sm @error('comportamental.'.$index) is-invalid @enderror" {{ $isReadOnly ? 'disabled' : '' }}>
                                    <option value="">Seleccione una competencia...</option>
                                    @foreach($competenciasDisponibles as $comp)
                                        <option value="{{ $comp->id }}">{{ $comp->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('comportamental.'.$index) <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-md-8">
                                <label class="form-label fw-bold text-muted small">Conductas Asociadas (Seleccione 3 a 4)</label>
                                @if(!empty($cc['competencia_id']) && isset($conductasPorCompetencia[$cc['competencia_id']]))
                                    <div class="row g-2">
                                        @foreach($conductasPorCompetencia[$cc['competencia_id']] as $conducta)
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="conducta_{{ $index }}_{{ $conducta->id }}" 
                                                           wire:click="toggleConducta({{ $index }}, {{ $conducta->id }})"
                                                           {{ in_array($conducta->id, $cc['conductas_ids']) ? 'checked' : '' }}
                                                           {{ $isReadOnly ? 'disabled' : '' }}>
                                                    <label class="form-check-label small text-dark" for="conducta_{{ $index }}_{{ $conducta->id }}">
                                                        {{ $conducta->descripcion }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-muted small fst-italic py-2">
                                        Seleccione una competencia para ver las conductas disponibles.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Acciones Flujo -->
        <div class="card shadow-sm">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <strong>Rol actual:</strong> <span class="text-primary text-uppercase">{{ $rolActual }}</span>
                </div>
                
                <div class="d-flex gap-2">
                    @if($rolActual == 'evaluado')
                        @if(!$concertacion || $concertacion->estado == 'borrador')
                            <button wire:click="saveDraft" class="btn btn-outline-secondary rounded-pill px-4">Guardar Borrador</button>
                            <button wire:click="sendToEvaluador" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="bi bi-send me-1"></i> Enviar a Revisión
                            </button>
                        @endif
                    @endif
                    
                    @if($rolActual == 'evaluador')
                        <button wire:click="fixDeOficio" class="btn btn-outline-danger rounded-pill px-4">
                            <i class="bi bi-exclamation-triangle me-1"></i> Fijar de Oficio
                        </button>
                        <button wire:click="approve" class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Aprobar Concertación
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
