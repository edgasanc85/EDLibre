<?php

use App\Livewire\Concertacion\ConcertacionComponent;
use App\Livewire\MisEvaluados\MisEvaluadosComponent;
use App\Models\CompromisoFuncional;
use App\Models\Concertacion;
use App\Models\Dependencia;
use App\Models\Evaluado;
use App\Models\Evaluador;
use App\Models\Nivel;
use App\Models\Periodo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->dependencia = Dependencia::create([
        'nombre' => 'Dirección de Talento Humano',
        'activo' => true,
    ]);

    $this->nivel = Nivel::create([
        'nombre' => 'Asistencial',
        'activo' => true,
    ]);

    $this->userEvaluador = User::factory()->create([
        'name' => 'Jefe de Dependencia',
    ]);

    $this->evaluador = Evaluador::create([
        'user_id' => $this->userEvaluador->id,
        'dependencia_id' => $this->dependencia->id,
        'cargo' => 'Jefe TH',
        'fecha_ingreso' => '2026-01-01',
        'activo' => true,
    ]);

    $this->userEvaluado = User::factory()->create([
        'name' => 'Auxiliar Administrativo',
    ]);

    $this->evaluado = Evaluado::create([
        'user_id' => $this->userEvaluado->id,
        'dependencia_id' => $this->dependencia->id,
        'nivel_id' => $this->nivel->id,
        'cargo' => 'Auxiliar',
        'fecha_ingreso' => '2026-01-01',
        'activo' => true,
    ]);

    $this->periodo = Periodo::create([
        'vigencia' => '2026',
        'fecha_inicio' => '2026-02-01',
        'fecha_fin' => '2027-01-31',
        'activo' => true,
    ]);

    $this->concertacion = Concertacion::create([
        'evaluado_id' => $this->evaluado->id,
        'evaluador_id' => null,
        'periodo_id' => $this->periodo->id,
        'estado' => 'en_revision',
        'activo' => true,
    ]);

    $this->cf1 = CompromisoFuncional::create([
        'evaluado_id' => $this->evaluado->id,
        'periodo_id' => $this->periodo->id,
        'concertacion_id' => $this->concertacion->id,
        'verbo' => 'Archivar',
        'objeto' => 'documentos',
        'condicion' => 'semanalmente',
        'peso' => 45,
        'activo' => true,
    ]);

    $this->cf2 = CompromisoFuncional::create([
        'evaluado_id' => $this->evaluado->id,
        'periodo_id' => $this->periodo->id,
        'concertacion_id' => $this->concertacion->id,
        'verbo' => 'Digitar',
        'objeto' => 'información',
        'condicion' => 'diariamente',
        'peso' => 40,
        'activo' => true,
    ]);

    $this->cf3 = CompromisoFuncional::create([
        'evaluado_id' => $this->evaluado->id,
        'periodo_id' => $this->periodo->id,
        'concertacion_id' => $this->concertacion->id,
        'verbo' => 'Atender',
        'objeto' => 'usuarios',
        'condicion' => 'cordialmente',
        'peso' => 0,
        'activo' => true,
    ]);
});

test('el evaluador puede aprobar una concertacion pendiente directamente desde MisEvaluadosComponent', function () {
    $this->actingAs($this->userEvaluador);

    expect($this->concertacion->estado)->toBe('en_revision');

    Livewire::test(MisEvaluadosComponent::class)
        ->assertSee('Pendiente Aprobación')
        ->assertSee('Aprobar')
        ->call('approveConcertacion', $this->concertacion->id)
        ->assertSee('Concertación aprobada exitosamente');

    $this->concertacion->refresh();
    expect($this->concertacion->estado)->toBe('aprobado');
    expect($this->concertacion->evaluador_id)->toBe($this->evaluador->id);
    expect($this->concertacion->fecha_aprobacion_evaluador)->not->toBeNull();
});

test('el evaluador puede ingresar a ConcertacionComponent y aprobar la concertacion', function () {
    // Configurar compromisos funcionales sumando 85% y 3 compromisos comportamentales con conductas
    $this->cf1->update(['peso' => 45]);
    $this->cf2->update(['peso' => 40]);
    $this->cf3->update(['peso' => 0]);

    $this->actingAs($this->userEvaluador);

    Livewire::test(ConcertacionComponent::class, [
        'evaluado_id' => $this->evaluado->id,
        'periodo_id' => $this->periodo->id,
    ])
        ->assertSee('Aprobar Concertación');
});
