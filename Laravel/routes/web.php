<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/', [FileController::class, 'create'])->name('upload.form');
Route::post('/upload', [FileController::class, 'store'])->name('upload.store');
Route::get('/d/{hash}', [FileController::class, 'show'])->name('file.show');
Route::get('/download/{hash}', [FileController::class, 'download'])->name('file.download');
