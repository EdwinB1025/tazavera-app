<?php

use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\OfferingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('home');
    Route::resource('offerings', OfferingController::class)->only(['index', 'show'])
        ->names([
            'index' => 'offerings',
        ]);
    Route::resource('evaluations', EvaluationController::class)->names([
        'index' => 'evaluations',
    ]);
});

require __DIR__ . '/settings.php';
