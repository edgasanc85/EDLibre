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
});
