<?php

use App\Http\Controllers\DiffController;
use Illuminate\Support\Facades\Route;

Route::get('/diff', [DiffController::class, 'index'])->name('diff.index');
Route::post('/diff', [DiffController::class, 'store'])->name('diff.store');
