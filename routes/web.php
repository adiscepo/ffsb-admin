<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route::view('/', 'welcome')->name('home');
Route::get('/', function (Request $request) {
    if (Auth::check())
        return redirect('/dashboard');
    return view('welcome');
})->name('home');
// Route::redirect('/', 'dashboard', 301);

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

    Route::livewire('production_house', 'pages::production_houses.index')->name('production_houses');

    // Route::livewire('edition', 'pages::edition.index')->name('edition');

    // Route::livewire('meetings', 'pages::admin.meetings.index')->name('meetings');
    Route::livewire('meetings/{id?}', 'pages::admin.meetings.index')->name('meetings');

    // Bugs
    Route::livewire('support/bugs/report', 'pages::support.bugs.report')->name('support.bugs.report');
    Route::livewire('support/bugs', 'pages::support.bugs.list')->name('support.bugs.list');
    Route::livewire('support/bug/{id}', 'pages::support.bugs.single')->name('support.bugs.single');
});

require __DIR__ . '/settings.php';
