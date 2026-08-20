<div>
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-dark fw-bold mb-0">Gestión de Evidencias</h1>
            <a href="{{ route('mis-compromisos') }}" class="btn btn-outline-secondary rounded-pill shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body bg-light border-bottom border-info border-3 rounded-top">
                <h5 class="mb-1 text-info fw-bold"><i class="bi bi-info-circle me-2"></i> Información del Periodo</h5>
                <p class="mb-0 text-muted">Estás registrando evidencias continuas para la vigencia <strong>{{ $concertacion->periodo->vigencia }}</strong>.</p>
                
                @if(session()->has('message_general'))
                    <div class="alert alert-success mt-3 mb-0 py-2 small">
                        {{ session('message_general') }}
                    </div>
                @endif
            </div>
        </div>

        @foreach($concertacion->compromisosFuncionals as $cf)
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-list-check text-primary me-2"></i> Compromiso Funcional: {{ $cf->verbo }} {{ $cf->objeto }}
                    </h5>
                    <p class="text-muted small mb-0 mt-1"><i class="bi bi-record-circle text-secondary"></i> Condición: {{ $cf->condicion }} | Peso: {{ $cf->peso }}%</p>
                </div>
                
                <div class="card-body bg-light text-dark">
                    <!-- Formulario de Nueva Evidencia -->
                    @if(!$concertacion->evidencias_enviadas)
                        <form wire:submit.prevent="saveEvidencia({{ $cf->id }})" class="mb-4 bg-white p-4 rounded-3 shadow-sm border text-dark">
                            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-plus-circle text-success me-1"></i> Añadir Nueva Evidencia</h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark">Descripción de la Evidencia</label>
                                    <input type="text" wire:model="evidencias_nuevas.{{ $cf->id }}.descripcion" class="form-control text-dark" placeholder="Ej. Informe de gestión Q1..." required>
                                    @error("evidencias_nuevas.{$cf->id}.descripcion") <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-dark">URL / Enlace (Drive, OneDrive)</label>
                                    <input type="url" wire:model="evidencias_nuevas.{{ $cf->id }}.ubicacion" class="form-control text-dark" placeholder="https://..." required>
                                    @error("evidencias_nuevas.{$cf->id}.ubicacion") <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100 shadow-sm fw-bold"><i class="bi bi-cloud-upload me-1"></i> Guardar</button>
                                </div>
                            </div>
                            
                            @if (session()->has("message_{$cf->id}"))
                                <div class="alert alert-success mt-3 mb-0 py-2 small">
                                    {{ session("message_{$cf->id}") }}
                                </div>
                            @endif
                        </form>
                    @endif

                    <!-- Lista de Evidencias Actuales -->
                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-folder2-open text-primary me-1"></i> Evidencias Registradas ({{ $cf->evidencias->where('activo', true)->count() }})</h6>
                    
                    @if($cf->evidencias->where('activo', true)->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered bg-white mb-0 text-dark shadow-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-dark fw-bold" style="width: 40%">Descripción</th>
                                        <th class="text-dark fw-bold" style="width: 40%">Enlace</th>
                                        <th class="text-dark fw-bold" style="width: 10%">Fecha</th>
                                        @if(!$concertacion->evidencias_enviadas)
                                            <th class="text-dark fw-bold text-center" style="width: 10%">Acciones</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cf->evidencias->where('activo', true) as $evidencia)
                                        <tr>
                                            <td class="align-middle text-dark fw-semibold">{{ $evidencia->descripcion }}</td>
                                            <td class="align-middle">
                                                <a href="{{ $evidencia->ubicacion }}" target="_blank" class="text-primary text-truncate d-inline-block text-decoration-none" style="max-width: 300px;">
                                                    <i class="bi bi-link-45deg me-1"></i> {{ $evidencia->ubicacion }}
                                                </a>
                                            </td>
                                            <td class="align-middle small text-muted">{{ $evidencia->created_at->format('d/m/Y') }}</td>
                                            @if(!$concertacion->evidencias_enviadas)
                                                <td class="align-middle text-center">
                                                    <button wire:click="deleteEvidencia({{ $evidencia->id }})" class="btn btn-sm btn-outline-danger border-0" onclick="confirm('¿Seguro que deseas eliminar esta evidencia?') || event.stopImmediatePropagation()" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted small fst-italic mb-0"><i class="bi bi-info-circle me-1"></i> Aún no has registrado evidencias para este compromiso.</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if(!$concertacion->evidencias_enviadas)
        <div class="card shadow border-0 mt-4 mb-5 mx-4" style="background-color: var(--ea-primary); color: white;">
            <div class="card-body text-center py-4">
                <h5 class="fw-bold mb-3">¿Terminaste de cargar todas tus evidencias?</h5>
                <p class="small mb-4" style="color: rgba(255,255,255,0.8);">Una vez enviadas, las evidencias quedarán registradas para tu evaluación y ya no podrás modificarlas ni añadir nuevas para este periodo.</p>
                <button wire:click="sendEvidencias" class="btn btn-light btn-lg rounded-pill px-5 shadow-sm fw-bold text-primary" onclick="confirm('¿Estás seguro de ENVIAR definitivamente tus evidencias? Esta acción no se puede deshacer.') || event.stopImmediatePropagation()">
                    <i class="bi bi-send-check me-2"></i> Enviar Evidencias al Evaluador
                </button>
            </div>
        </div>
    @else
        <div class="alert alert-success mt-4 mb-5 mx-4 shadow-sm border-0 d-flex align-items-center">
            <i class="bi bi-lock-fill fs-3 me-3"></i>
            <div>
                <h5 class="fw-bold mb-1">Evidencias Enviadas</h5>
                <p class="mb-0 small">Tus evidencias ya fueron enviadas al evaluador y se encuentran bloqueadas para modificaciones.</p>
            </div>
        </div>
    @endif
</div>
