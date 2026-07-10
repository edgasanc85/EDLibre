<div>
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-dark fw-bold">Gestión de Periodos</h1>
            <button wire:click="create" class="btn btn-primary d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Nuevo Periodo
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
                            <h5 class="modal-title text-dark fw-bold"><i class="bi bi-calendar-event me-2" style="color: var(--ea-primary);"></i>{{ $isEditMode ? 'Editar Periodo' : 'Crear Nuevo Periodo' }}</h5>
                            <button type="button" wire:click="resetInputFields" class="btn-close" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">Vigencia <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="vigencia" class="form-control @error('vigencia') is-invalid @enderror" placeholder="Ej: {{ date('Y') }}-{{ date('Y') + 1 }}" maxlength="20">
                                    @error('vigencia') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted fw-bold">Fecha de Inicio <span class="text-danger">*</span></label>
                                        <input type="date" wire:model="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror">
                                        @error('fecha_inicio') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted fw-bold">Fecha de Fin <span class="text-danger">*</span></label>
                                        <input type="date" wire:model="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror">
                                        @error('fecha_fin') <span class="text-danger small">{{ $message }}</span> @enderror
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
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm ps-4" placeholder="Buscar por año...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute text-muted" style="top: 50%; left: 10px; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 text-muted fw-bold">Vigencia</th>
                                <th class="py-3 px-4 text-muted fw-bold">Rango de Fechas</th>
                                <th class="py-3 px-4 text-muted fw-bold text-center">Estado</th>
                                <th class="py-3 px-4 text-muted fw-bold text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                <tr>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 36px; height: 36px;">
                                                <i class="bi bi-calendar-event"></i>
                                            </div>
                                            <span class="text-dark fw-bold fs-5">{{ $record->vigencia }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="d-flex flex-column text-muted small">
                                            <span><strong>Inicio:</strong> {{ $record->fecha_inicio->format('d/m/Y') }}</span>
                                            <span><strong>Fin:</strong> {{ $record->fecha_fin->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $isCurrent = $now->between($record->fecha_inicio, $record->fecha_fin);
                                            $isPast = $now->greaterThan($record->fecha_fin);
                                        @endphp
                                        
                                        @if($isCurrent)
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill border border-success border-opacity-25">
                                                En Curso
                                            </span>
                                        @elseif($isPast)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill border border-secondary border-opacity-25">
                                                Finalizado
                                            </span>
                                        @else
                                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill border border-info border-opacity-25">
                                                Próximo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 align-middle text-end">
                                        <button wire:click="edit({{ $record->id }})" class="btn btn-sm btn-icon btn-outline-secondary border-0" title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button onclick="if(confirm('¿Está seguro de eliminar este periodo?')) { @this.delete({{ $record->id }}) }" class="btn btn-sm btn-icon btn-outline-danger border-0" title="Eliminar">
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
                                        No se encontraron periodos registrados.
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
