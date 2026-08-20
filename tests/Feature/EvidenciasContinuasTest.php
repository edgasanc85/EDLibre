<?php

use App\Livewire\Evidencias\EvidenciaComponent;
use App\Models\CompromisoFuncional;
use App\Models\Concertacion;
use App\Models\Dependencia;
use App\Models\Evaluado;
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
        'nombre' => 'Profesional',
        'activo' => true,
    ]);

    $this->userEvaluado = User::factory()->create([
        'name' => 'Funcionario Evaluado',
    ]);

    $this->evaluado = Evaluado::create([
        'user_id' => $this->userEvaluado->id,
        'dependencia_id' => $this->dependencia->id,
        'nivel_id' => $this->nivel->id,
        'cargo' => 'Profesional Universitario',
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
        'periodo_id' => $this->periodo->id,
        'estado' => 'aprobado',
        'activo' => true,
    ]);

    $this->cf = CompromisoFuncional::create([
        'evaluado_id' => $this->evaluado->id,
        'periodo_id' => $this->periodo->id,
        'concertacion_id' => $this->concertacion->id,
        'verbo' => 'Elaborar',
        'objeto' => 'informes técnicos',
        'condicion' => 'mensualmente',
        'peso' => 30,
        'activo' => true,
    ]);
});

test('el evaluado puede registrar múltiples evidencias de forma continua a lo largo del periodo asociadas a una evaluacion', function () {
    $this->actingAs($this->userEvaluado);

    $component = Livewire::test(EvidenciaComponent::class, ['concertacion_id' => $this->concertacion->id]);

    $this->concertacion->refresh();
    $eval1 = $this->concertacion->evaluaciones()->where('causal', 'Parcial primer semestre')->first();
    $eval2 = $this->concertacion->evaluaciones()->where('causal', 'Parcial segundo semestre')->first();

    expect($eval1)->not->toBeNull();
    expect($eval2)->not->toBeNull();

    // Registro de primera evidencia asociada al Semestre 1
    $component
        ->set("evidencias_nuevas.{$this->cf->id}.descripcion", 'Informe Q1 Enero - Marzo')
        ->set("evidencias_nuevas.{$this->cf->id}.ubicacion", 'https://drive.google.com/informe-q1')
        ->set("evidencias_nuevas.{$this->cf->id}.evaluacion_id", $eval1->id)
        ->call('saveEvidencia', $this->cf->id)
        ->assertSee('Informe Q1 Enero - Marzo')
        ->assertSee('Parcial primer semestre');

    // Registro de segunda evidencia en otro momento asociada al Semestre 2
    $component
        ->set("evidencias_nuevas.{$this->cf->id}.descripcion", 'Informe Q2 Abril - Junio')
        ->set("evidencias_nuevas.{$this->cf->id}.ubicacion", 'https://drive.google.com/informe-q2')
        ->set("evidencias_nuevas.{$this->cf->id}.evaluacion_id", $eval2->id)
        ->call('saveEvidencia', $this->cf->id)
        ->assertSee('Informe Q1 Enero - Marzo')
        ->assertSee('Informe Q2 Abril - Junio')
        ->assertSee('Parcial segundo semestre');

    $evidencias = $this->cf->evidencias()->where('activo', true)->get();
    expect($evidencias->count())->toBe(2);
    expect($evidencias[0]->evaluacion_id)->toBe($eval1->id);
    expect($evidencias[1]->evaluacion_id)->toBe($eval2->id);
});
