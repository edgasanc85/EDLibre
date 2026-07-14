<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/users', App\Livewire\Users\UserComponent::class)->name('users');
    Route::get('/dependencias', App\Livewire\Dependencias\DependenciaComponent::class)->name('dependencias');
    Route::get('/niveles', App\Livewire\Niveles\NivelComponent::class)->name('niveles');
    Route::get('/competencias', App\Livewire\Competencias\CompetenciaComponent::class)->name('competencias');
    Route::get('/periodos', App\Livewire\Periodos\PeriodoComponent::class)->name('periodos');
    Route::get('/evaluadores', App\Livewire\Evaluadores\EvaluadorComponent::class)->name('evaluadores');
    Route::get('/evaluados', App\Livewire\Evaluados\EvaluadoComponent::class)->name('evaluados');
    Route::get('/concertacion/{id}/pdf', [App\Http\Controllers\ConcertacionPdfController::class, 'export'])->name('concertacion.pdf');
    Route::get('/concertacion/{evaluado_id}/{periodo_id}', App\Livewire\Concertacion\ConcertacionComponent::class)->name('concertacion');
    Route::get('/mis-compromisos', App\Livewire\MisCompromisos\MisCompromisosComponent::class)->name('mis-compromisos');
    Route::get('/mis-evaluados', App\Livewire\MisEvaluados\MisEvaluadosComponent::class)->name('mis-evaluados');
});
