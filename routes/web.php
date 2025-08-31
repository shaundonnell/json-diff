<?php

use App\Http\Controllers\DiffController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/diff', [DiffController::class, 'index'])->name('diff.index');
Route::post('/diff', [DiffController::class, 'store'])->name('diff.store');
