<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('home');
});

require __DIR__ . '/settings.php';
