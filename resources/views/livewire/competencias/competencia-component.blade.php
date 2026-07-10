<div>
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-dark fw-bold">Gestión de Competencias y Conductas</h1>
            <button wire:click="create" class="btn btn-primary d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Nueva Competencia
            </button>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($isFormOpen)
            <div class="modal fade show" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog" wire:click.self="resetInputFields" wire:keydown.escape.window="resetInputFields">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <div class="modal-header bg-light border-0 py-3 px-4 border-bottom">
                            <h5 class="modal-title text-dark fw-bold"><i class="bi bi-star me-2" style="color: var(--ea-primary);"></i>{{ $isEditMode ? 'Editar Competencia' : 'Crear Nueva Competencia' }}</h5>
                            <button type="button" wire:click="resetInputFields" class="btn-close" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                            <div class="modal-body p-4">
                                <div class="row g-4">
                                    <!-- Datos de la Competencia -->
                                    <div class="col-md-5 border-end">
                                        <h6 class="fw-bold mb-3 text-dark">Datos Principales</h6>
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Nivel Jerárquico <span class="text-danger">*</span></label>
                                            <select wire:model="nivel_id" class="form-select @error('nivel_id') is-invalid @enderror">
                                                <option value="">Seleccione un Nivel</option>
                                                @foreach($niveles as $nivel)
                                                    <option value="{{ $nivel->id }}">
                                                        {{ $nivel->nombre }} @if($nivel->id == 1) (Aplica a todos los niveles) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('nivel_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Nombre de la Competencia <span class="text-danger">*</span></label>
                                            <input type="text" wire:model="nombre" class="form-control @error('nombre') is-invalid @enderror" placeholder="Ej: Liderazgo, Trabajo en equipo...">
                                            @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Descripción General</label>
                                            <textarea wire:model="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4" placeholder="Explique brevemente qué evalúa esta competencia..."></textarea>
                                            @error('descripcion') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Conductas Asociadas -->
                                    <div class="col-md-7">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold mb-0 text-dark">Conductas a Evaluar</h6>
                                            <button type="button" wire:click="addConducta" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-plus-circle me-1"></i> Añadir Conducta
                                            </button>
                                        </div>
                                        
                                        <div class="conductas-container" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                                            @foreach($conductas as $index => $conducta)
                                                <div class="card mb-2 shadow-sm border-0 bg-light">
                                                    <div class="card-body p-2 d-flex gap-2 align-items-start">
                                                        <div class="flex-grow-1">
                                                            <textarea wire:model="conductas.{{ $index }}.descripcion" class="form-control form-control-sm @error('conductas.'.$index.'.descripcion') is-invalid @enderror" rows="2" placeholder="Describa el comportamiento observable..."></textarea>
                                                            @error('conductas.'.$index.'.descripcion') <span class="text-danger small">{{ $message }}</span> @enderror
                                                        </div>
                                                        <button type="button" wire:click="removeConducta({{ $index }})" class="btn btn-sm btn-icon btn-outline-danger border-0" title="Eliminar conducta">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            @if(count($conductas) === 0)
                                                <div class="text-center py-4 text-muted small">
                                                    <i class="bi bi-info-circle mb-2 d-block fs-4"></i>
                                                    Aún no has añadido conductas. Haz clic en "Añadir Conducta" para empezar.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light border-0 py-3 px-4 border-top">
                                <button type="button" wire:click="resetInputFields" class="btn btn-light rounded-2 border">Cancelar</button>
                                <button type="submit" class="btn btn-primary rounded-2">
                                    <span wire:loading wire:target="{{ $isEditMode ? 'update' : 'store' }}" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    {{ $isEditMode ? 'Actualizar' : 'Guardar' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <select wire:model.live="perPage" class="form-select form-select-sm" style="width: 70px;">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-muted small">registros</span>
                </div>
                <div class="position-relative" style="width: 250px;">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm ps-4" placeholder="Buscar competencias...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute text-muted" style="top: 50%; left: 10px; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 text-muted fw-bold">Competencia</th>
                                <th class="py-3 px-4 text-muted fw-bold">Nivel</th>
                                <th class="py-3 px-4 text-muted fw-bold text-center">Nº Conductas</th>
                                <th class="py-3 px-4 text-muted fw-bold text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                <tr>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 36px; height: 36px;">
                                                <i class="bi bi-star"></i>
                                            </div>
                                            <div>
                                                <div class="text-dark fw-semibold">{{ $record->nombre }}</div>
                                                <div class="text-muted small text-truncate" style="max-width: 300px;" title="{{ $record->descripcion }}">{{ Str::limit($record->descripcion, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        @if($record->nivel_id == 1)
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill border border-success border-opacity-25" title="Aplica transversalmente">
                                                <i class="bi bi-diagram-3-fill me-1"></i> Común a todos los niveles
                                            </span>
                                        @else
                                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill border border-info border-opacity-25">
                                                {{ $record->nivel ? $record->nivel->nombre : 'Sin Nivel' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1 rounded-pill">
                                            {{ $record->conductas()->active()->count() }} conductas
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 align-middle text-end">
                                        <button wire:click="edit({{ $record->id }})" class="btn btn-sm btn-icon btn-outline-secondary border-0" title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button onclick="if(confirm('¿Está seguro de eliminar esta competencia y todas sus conductas asociadas?')) { @this.delete({{ $record->id }}) }" class="btn btn-sm btn-icon btn-outline-danger border-0" title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-muted">
                                        <div class="mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        </div>
                                        No se encontraron competencias registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($records->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $records->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
