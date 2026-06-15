<?php

use App\Http\Controllers\DocuController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    // Route::resource('documentaries', DocuController::class);
    Route::livewire('docus', 'pages::docu.table')->name('docus');
    Route::livewire('docu/{id}', 'pages::docu.single')->name('docu');
    Route::livewire('evaluation/{id}/{user_id}', 'pages::evaluation.single')->name('eval-create');
    Route::livewire('evaluations', 'pages::evaluation.evaluations')->name('evaluations');
    Route::livewire('evaluations/{year}', 'pages::evaluation.evaluations')->name('evaluation');

    Route::livewire('programs', 'pages::programs.index')->name('programs');
    Route::livewire('programs/{year}', 'pages::programs.index');
    Route::livewire('program/{id}', 'pages::programs.single')->name('program');

    // Bugs
    Route::livewire('support/bugs/report', 'pages::support.bugs.report')->name('support.bugs.report');
    Route::livewire('support/bugs', 'pages::support.bugs.list')->name('support.bugs.list');
    Route::livewire('support/bug/{id}', 'pages::support.bugs.single')->name('support.bugs.single');
});

require __DIR__ . '/settings.php';
