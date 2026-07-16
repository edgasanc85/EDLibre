<?php

use App\Http\Controllers\ConcertacionPdfController;
use App\Http\Controllers\HomeController;
use App\Livewire\Competencias\CompetenciaComponent;
use App\Livewire\Concertacion\ConcertacionComponent;
use App\Livewire\Dependencias\DependenciaComponent;
use App\Livewire\Evaluaciones\EvaluacionComponent;
use App\Livewire\Evaluadores\EvaluadorComponent;
use App\Livewire\Evaluados\EvaluadoComponent;
use App\Livewire\Evidencias\EvidenciaComponent;
use App\Livewire\MisCompromisos\MisCompromisosComponent;
use App\Livewire\MisEvaluados\MisEvaluadosComponent;
use App\Livewire\Niveles\NivelComponent;
use App\Livewire\Periodos\PeriodoComponent;
use App\Livewire\Users\UserComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/users', UserComponent::class)->name('users');
    Route::get('/dependencias', DependenciaComponent::class)->name('dependencias');
    Route::get('/niveles', NivelComponent::class)->name('niveles');
    Route::get('/competencias', CompetenciaComponent::class)->name('competencias');
    Route::get('/periodos', PeriodoComponent::class)->name('periodos');
    Route::get('/evaluadores', EvaluadorComponent::class)->name('evaluadores');
    Route::get('/evaluados', EvaluadoComponent::class)->name('evaluados');
    Route::get('/concertacion/{id}/pdf', [ConcertacionPdfController::class, 'export'])->name('concertacion.pdf');
    Route::get('/evaluacion/{id}/pdf', [\App\Http\Controllers\EvaluacionPdfController::class, 'export'])->name('evaluacion.pdf');
    Route::get('/concertacion/{evaluado_id}/{periodo_id}', ConcertacionComponent::class)->name('concertacion');
    Route::get('/evidencias/{concertacion_id}', EvidenciaComponent::class)->name('evidencias');
    Route::get('/evaluaciones/{concertacion_id}', EvaluacionComponent::class)->name('evaluaciones');
    Route::get('/mis-compromisos', MisCompromisosComponent::class)->name('mis-compromisos');
    Route::get('/mis-evaluados', MisEvaluadosComponent::class)->name('mis-evaluados');
});
