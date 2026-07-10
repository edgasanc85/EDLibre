<div>
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-dark fw-bold">Gestión de Niveles</h1>
            <button wire:click="create" class="btn btn-primary d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Nuevo Nivel
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
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <div class="modal-header bg-light border-0 py-3 px-4 border-bottom">
                            <h5 class="modal-title text-dark fw-bold"><i class="bi bi-layers me-2" style="color: var(--ea-primary);"></i>{{ $isEditMode ? 'Editar Nivel' : 'Crear Nuevo Nivel' }}</h5>
                            <button type="button" wire:click="resetInputFields" class="btn-close" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">Nombre del Nivel <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nombre" class="form-control @error('nombre') is-invalid @enderror" placeholder="Ej: Asesor, Directivo, Profesional...">
                                    @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
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
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm ps-4" placeholder="Buscar niveles...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute text-muted" style="top: 50%; left: 10px; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 text-muted fw-bold">Nombre del Nivel</th>
                                <th class="py-3 px-4 text-muted fw-bold text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                <tr>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($record->id == 1)
                                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center text-success fw-bold" style="width: 36px; height: 36px;">
                                                    <i class="bi bi-diagram-3-fill"></i>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-semibold">{{ $record->nombre }}</span>
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill border border-success border-opacity-25" style="width: fit-content; font-size: 0.7rem;">
                                                        Aplica a todos los niveles
                                                    </span>
                                                </div>
                                            @else
                                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 36px; height: 36px;">
                                                    <i class="bi bi-layers"></i>
                                                </div>
                                                <span class="text-dark fw-semibold">{{ $record->nombre }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle text-end">
                                        <button wire:click="edit({{ $record->id }})" class="btn btn-sm btn-icon btn-outline-secondary border-0" title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button onclick="if(confirm('¿Está seguro de eliminar este nivel?')) { @this.delete({{ $record->id }}) }" class="btn btn-sm btn-icon btn-outline-danger border-0" title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 text-center text-muted">
                                        <div class="mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        </div>
                                        No se encontraron niveles registrados.
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
