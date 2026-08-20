<?php

use App\Livewire\Evaluaciones\EvaluacionComponent;
use App\Models\Competencia;
use App\Models\CompromisoComportamental;
use App\Models\CompromisoFuncional;
use App\Models\Concertacion;
use App\Models\Conducta;
use App\Models\Dependencia;
use App\Models\Evaluacion;
use App\Models\EvaluacionComportamental;
use App\Models\EvaluacionCompromiso;
use App\Models\Evaluado;
use App\Models\Evaluador;
use App\Models\Nivel;
use App\Models\Periodo;
use App\Models\User;
use App\Services\EvaluacionConsolidacionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->dependencia = Dependencia::create([
        'nombre' => 'Dirección de Tecnología',
        'activo' => true,
    ]);

    $this->nivel = Nivel::create([
        'nombre' => 'Profesional',
        'activo' => true,
    ]);

    $this->userEvaluador = User::factory()->create([
        'name' => 'Jefe Evaluador',
    ]);

    $this->evaluador = Evaluador::create([
        'user_id' => $this->userEvaluador->id,
        'dependencia_id' => $this->dependencia->id,
        'cargo' => 'Director TI',
        'fecha_ingreso' => '2026-01-01',
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
        'evaluador_id' => $this->evaluador->id,
        'periodo_id' => $this->periodo->id,
        'estado' => 'aprobado',
        'activo' => true,
    ]);

    $this->compromisoFuncional1 = CompromisoFuncional::create([
        'evaluado_id' => $this->evaluado->id,
        'periodo_id' => $this->periodo->id,
        'concertacion_id' => $this->concertacion->id,
        'verbo' => 'Desarrollar',
        'objeto' => 'módulo de evaluación',
        'condicion' => 'según estándares',
        'peso' => 60,
        'activo' => true,
    ]);

    $this->compromisoFuncional2 = CompromisoFuncional::create([
        'evaluado_id' => $this->evaluado->id,
        'periodo_id' => $this->periodo->id,
        'concertacion_id' => $this->concertacion->id,
        'verbo' => 'Implementar',
        'objeto' => 'seguridad ISO 27001',
        'condicion' => 'conforme a controles',
        'peso' => 40,
        'activo' => true,
    ]);

    $this->competencia = Competencia::create([
        'nivel_id' => $this->nivel->id,
        'nombre' => 'Orientación a Resultados',
        'descripcion' => 'Cumplir metas institucionales',
        'activo' => true,
    ]);

    $this->conducta = Conducta::create([
        'competencia_id' => $this->competencia->id,
        'descripcion' => 'Entrega informes a tiempo',
        'activo' => true,
    ]);

    $this->compromisoComportamental = CompromisoComportamental::create([
        'evaluado_id' => $this->evaluado->id,
        'periodo_id' => $this->periodo->id,
        'concertacion_id' => $this->concertacion->id,
        'competencia_id' => $this->competencia->id,
        'activo' => true,
    ]);
    $this->compromisoComportamental->conductas()->attach($this->conducta->id);
});

test('el servicio calcula la consolidacion definitiva de forma ponderada por dias exacta', function () {
    // 1er Semestre: 181 días (2026-02-01 al 2026-07-31)
    $eval1 = Evaluacion::create([
        'concertacion_id' => $this->concertacion->id,
        'causal' => 'Parcial primer semestre',
        'estado' => 'aceptada',
        'puntaje_funcional_obtenido' => 80.00,
        'puntaje_comportamental_obtenido' => 14.00,
        'periodo_evaluado_inicio' => '2026-02-01',
        'periodo_evaluado_fin' => '2026-07-31',
        'fecha_evaluacion' => now(),
        'activo' => true,
    ]);

    EvaluacionCompromiso::create([
        'evaluacion_id' => $eval1->id,
        'compromiso_funcional_id' => $this->compromisoFuncional1->id,
        'calificacion' => 90,
        'activo' => true,
    ]);

    EvaluacionCompromiso::create([
        'evaluacion_id' => $eval1->id,
        'compromiso_funcional_id' => $this->compromisoFuncional2->id,
        'calificacion' => 100,
        'activo' => true,
    ]);

    EvaluacionComportamental::create([
        'evaluacion_id' => $eval1->id,
        'compromiso_comportamental_id' => $this->compromisoComportamental->id,
        'conducta_id' => $this->conducta->id,
        'calificacion' => 93.33,
        'activo' => true,
    ]);

    // 2do Semestre: 184 días (2026-08-01 al 2027-01-31)
    $eval2 = Evaluacion::create([
        'concertacion_id' => $this->concertacion->id,
        'causal' => 'Parcial segundo semestre',
        'estado' => 'calificada',
        'puntaje_funcional_obtenido' => 85.00,
        'puntaje_comportamental_obtenido' => 15.00,
        'periodo_evaluado_inicio' => '2026-08-01',
        'periodo_evaluado_fin' => '2027-01-31',
        'fecha_evaluacion' => now(),
        'activo' => true,
    ]);

    EvaluacionCompromiso::create([
        'evaluacion_id' => $eval2->id,
        'compromiso_funcional_id' => $this->compromisoFuncional1->id,
        'calificacion' => 100,
        'activo' => true,
    ]);

    EvaluacionCompromiso::create([
        'evaluacion_id' => $eval2->id,
        'compromiso_funcional_id' => $this->compromisoFuncional2->id,
        'calificacion' => 100,
        'activo' => true,
    ]);

    EvaluacionComportamental::create([
        'evaluacion_id' => $eval2->id,
        'compromiso_comportamental_id' => $this->compromisoComportamental->id,
        'conducta_id' => $this->conducta->id,
        'calificacion' => 100,
        'activo' => true,
    ]);

    $service = app(EvaluacionConsolidacionService::class);
    $consolidacion = $service->generarConsolidacionDefinitiva($this->concertacion);

    expect($consolidacion)->not->toBeNull();
    expect($consolidacion->causal)->toBe('Consolidación definitiva');
    expect($consolidacion->estado)->toBe('calificada');

    // Total días = 181 + 184 = 365
    // Ponderado funcional: (80.00 * 181 + 85.00 * 184) / 365 = (14480 + 15640) / 365 = 30120 / 365 = 82.52
    expect((float) $consolidacion->puntaje_funcional_obtenido)->toBe(82.52);

    // Ponderado comportamental: (14.00 * 181 + 15.00 * 184) / 365 = (2534 + 2760) / 365 = 5294 / 365 = 14.50
    expect((float) $consolidacion->puntaje_comportamental_obtenido)->toBe(14.50);

    // Detalle de compromisos funcionales sincronizados
    $ec1 = EvaluacionCompromiso::where('evaluacion_id', $consolidacion->id)
        ->where('compromiso_funcional_id', $this->compromisoFuncional1->id)
        ->first();
    // (90 * 181 + 100 * 184) / 365 = (16290 + 18400) / 365 = 34690 / 365 = 95.04
    expect((float) $ec1->calificacion)->toBe(95.04);

    // Unicidad / Idempotencia: si se vuelve a llamar, no crea otro registro
    $service->generarConsolidacionDefinitiva($this->concertacion);
    $conteo = Evaluacion::where('concertacion_id', $this->concertacion->id)
        ->where('causal', 'Consolidación definitiva')
        ->count();
    expect($conteo)->toBe(1);
});

test('notificar el segundo semestre en Livewire genera automaticamente la consolidacion definitiva', function () {
    // 1er Semestre ya calificado y aceptado
    $eval1 = Evaluacion::create([
        'concertacion_id' => $this->concertacion->id,
        'causal' => 'Parcial primer semestre',
        'estado' => 'aceptada',
        'puntaje_funcional_obtenido' => 80.00,
        'puntaje_comportamental_obtenido' => 14.00,
        'periodo_evaluado_inicio' => '2026-02-01',
        'periodo_evaluado_fin' => '2026-07-31',
        'fecha_evaluacion' => now(),
        'activo' => true,
    ]);

    // 2do Semestre en borrador
    $eval2 = Evaluacion::create([
        'concertacion_id' => $this->concertacion->id,
        'causal' => 'Parcial segundo semestre',
        'estado' => 'en_revision',
        'puntaje_funcional_obtenido' => 85.00,
        'puntaje_comportamental_obtenido' => 15.00,
        'periodo_evaluado_inicio' => '2026-08-01',
        'periodo_evaluado_fin' => '2027-01-31',
        'activo' => true,
    ]);

    $this->actingAs($this->userEvaluador);

    $keyConducta = $this->compromisoComportamental->id.'_'.$this->conducta->id;

    Livewire::test(EvaluacionComponent::class, ['concertacion_id' => $this->concertacion->id])
        ->call('openGradeModal', $eval2->id)
        ->set('calificaciones.'.$this->compromisoFuncional1->id, 100)
        ->set('calificaciones.'.$this->compromisoFuncional2->id, 100)
        ->set('calificaciones_comportamentales.'.$keyConducta, 100)
        ->call('notificarEvaluacion')
        ->assertSee('Evaluación Consolidada Definitiva');

    $consolidacion = Evaluacion::where('concertacion_id', $this->concertacion->id)
        ->where('causal', 'Consolidación definitiva')
        ->first();

    expect($consolidacion)->not->toBeNull();
    expect($consolidacion->estado)->toBe('calificada');

    // Ahora el evaluado inicia sesión y acepta la consolidación
    $this->actingAs($this->userEvaluado);

    Livewire::test(EvaluacionComponent::class, ['concertacion_id' => $this->concertacion->id])
        ->call('acceptEvaluacion', $consolidacion->id)
        ->assertSee('Evaluación aceptada exitosamente');

    $consolidacion->refresh();
    expect($consolidacion->estado)->toBe('aceptada');
});

test('crear consolidacion definitiva manualmente desde el modal en Livewire funciona correctamente', function () {
    // 1er Semestre aceptado
    Evaluacion::create([
        'concertacion_id' => $this->concertacion->id,
        'causal' => 'Parcial primer semestre',
        'estado' => 'aceptada',
        'puntaje_funcional_obtenido' => 75.00,
        'puntaje_comportamental_obtenido' => 13.00,
        'periodo_evaluado_inicio' => '2026-02-01',
        'periodo_evaluado_fin' => '2026-07-31',
        'fecha_evaluacion' => now(),
        'activo' => true,
    ]);

    $this->actingAs($this->userEvaluador);

    Livewire::test(EvaluacionComponent::class, ['concertacion_id' => $this->concertacion->id])
        ->set('causal', 'Consolidación definitiva')
        ->call('createEvaluacion')
        ->assertSee('Consolidación generada exitosamente');

    $consolidacion = Evaluacion::where('concertacion_id', $this->concertacion->id)
        ->where('causal', 'Consolidación definitiva')
        ->first();

    expect($consolidacion)->not->toBeNull();
    expect($consolidacion->estado)->toBe('calificada');
    expect((float) $consolidacion->puntaje_funcional_obtenido)->toBe(75.00);
});

test('descargar el PDF de una consolidacion definitiva aceptada genera el documento correctamente', function () {
    $consolidacion = Evaluacion::create([
        'concertacion_id' => $this->concertacion->id,
        'causal' => 'Consolidación definitiva',
        'estado' => 'aceptada',
        'puntaje_funcional_obtenido' => 82.50,
        'puntaje_comportamental_obtenido' => 14.50,
        'periodo_evaluado_inicio' => '2026-02-01',
        'periodo_evaluado_fin' => '2027-01-31',
        'fecha_evaluacion' => now(),
        'activo' => true,
    ]);

    EvaluacionCompromiso::create([
        'evaluacion_id' => $consolidacion->id,
        'compromiso_funcional_id' => $this->compromisoFuncional1->id,
        'calificacion' => 95,
        'activo' => true,
    ]);

    EvaluacionComportamental::create([
        'evaluacion_id' => $consolidacion->id,
        'compromiso_comportamental_id' => $this->compromisoComportamental->id,
        'conducta_id' => $this->conducta->id,
        'calificacion' => 96.66,
        'activo' => true,
    ]);

    $this->actingAs($this->userEvaluado);

    $response = $this->get(route('evaluacion.pdf', $consolidacion->id));
    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});

test('calificar con 100 cada item en el modal guarda correctamente 85 en funcional y 15 en comportamental', function () {
    $eval = Evaluacion::create([
        'concertacion_id' => $this->concertacion->id,
        'causal' => 'Parcial primer semestre',
        'estado' => 'en_revision',
        'periodo_evaluado_inicio' => '2026-02-01',
        'periodo_evaluado_fin' => '2026-07-31',
        'activo' => true,
    ]);

    $this->actingAs($this->userEvaluador);

    $keyConducta = $this->compromisoComportamental->id.'_'.$this->conducta->id;

    Livewire::test(EvaluacionComponent::class, ['concertacion_id' => $this->concertacion->id])
        ->call('openGradeModal', $eval->id)
        ->set('calificaciones.'.$this->compromisoFuncional1->id, 100)
        ->set('calificaciones.'.$this->compromisoFuncional2->id, 100)
        ->set('calificaciones_comportamentales.'.$keyConducta, 100)
        ->call('notificarEvaluacion')
        ->assertSee('Evaluación enviada al evaluado exitosamente');

    $eval->refresh();
    expect($eval->estado)->toBe('calificada');
    expect((float) $eval->puntaje_funcional_obtenido)->toBe(85.00);
    expect((float) $eval->puntaje_comportamental_obtenido)->toBe(15.00);

    // Verificar calificaciones individuales en bd
    $ec1 = EvaluacionCompromiso::where('evaluacion_id', $eval->id)
        ->where('compromiso_funcional_id', $this->compromisoFuncional1->id)
        ->first();
    expect((float) $ec1->calificacion)->toBe(100.00);

    $ec2 = EvaluacionCompromiso::where('evaluacion_id', $eval->id)
        ->where('compromiso_funcional_id', $this->compromisoFuncional2->id)
        ->first();
    expect((float) $ec2->calificacion)->toBe(100.00);

    $ecomp = EvaluacionComportamental::where('evaluacion_id', $eval->id)
        ->where('compromiso_comportamental_id', $this->compromisoComportamental->id)
        ->first();
    expect((float) $ecomp->calificacion)->toBe(100.00);
});

test('cuando todas las evaluaciones del periodo tienen 100 puntos la consolidacion definitiva obtiene exactamente 100 puntos', function () {
    // 1er Semestre con 100
    $eval1 = Evaluacion::create([
        'concertacion_id' => $this->concertacion->id,
        'causal' => 'Parcial primer semestre',
        'estado' => 'aceptada',
        'puntaje_funcional_obtenido' => 85.00,
        'puntaje_comportamental_obtenido' => 15.00,
        'periodo_evaluado_inicio' => '2026-02-01',
        'periodo_evaluado_fin' => '2026-07-31',
        'fecha_evaluacion' => now(),
        'activo' => true,
    ]);

    // 2do Semestre con 100
    $eval2 = Evaluacion::create([
        'concertacion_id' => $this->concertacion->id,
        'causal' => 'Parcial segundo semestre',
        'estado' => 'aceptada',
        'puntaje_funcional_obtenido' => 85.00,
        'puntaje_comportamental_obtenido' => 15.00,
        'periodo_evaluado_inicio' => '2026-08-01',
        'periodo_evaluado_fin' => '2027-01-31',
        'fecha_evaluacion' => now(),
        'activo' => true,
    ]);

    $service = app(EvaluacionConsolidacionService::class);
    $consolidacion = $service->generarConsolidacionDefinitiva($this->concertacion);

    expect($consolidacion)->not->toBeNull();
    expect((float) $consolidacion->puntaje_funcional_obtenido)->toBe(85.00);
    expect((float) $consolidacion->puntaje_comportamental_obtenido)->toBe(15.00);
    expect((float) ($consolidacion->puntaje_funcional_obtenido + $consolidacion->puntaje_comportamental_obtenido))->toBe(100.00);
});
