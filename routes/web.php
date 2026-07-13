<?php

use App\Http\Controllers\OfferingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('offerings', OfferingController::class)->only(['index', 'show']);
});

Route::view('dashboard', 'dashboard')->name('home');


require __DIR__ . '/settings.php';
