<?php

use App\Http\Controllers\DocuController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    // Route::resource('documentaries', DocuController::class);
    Route::livewire('docus', 'pages::docu.table')->name('docus');
    Route::livewire('docu/{id}', 'pages::docu.single')->name('docu');
    Route::livewire('evaluation/{id}/create', 'pages::evaluation.create')->name('eval-create');
});

require __DIR__.'/settings.php';
