<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return redirect()->route('files.list');
})->name('home');
Route::get('/login', function () {
    return redirect()->route('users.login');
})->name('login');

Route::prefix('/users')->group(function() {
    Route::get('/register', [UserController::class, 'register'])
        ->name('users.register');
    Route::post('/register', [UserController::class, 'registerMethod'])
        ->name('users.register.method');

    Route::get('/login', [UserController::class, 'login'])
        ->name('users.login');
    Route::post('/login', [UserController::class, 'loginMethod'])
        ->name('users.login.method');
});

Route::prefix('/users')->middleware('auth')->group(function() {
    Route::get('/logout', [UserController::class, 'logout'])
        ->name('users.logout');
    Route::post('/logout', [UserController::class, 'logoutMethod'])
        ->name('users.logout.method');

    Route::get('/me', [UserController::class, 'me'])
    ->name('users.me');
});

Route::prefix('/files')->middleware('auth')->group(function () {
    /**
     * FILES
     */
    Route::get('/upload/{path?}', [FileController::class, 'upload'])
        ->name('files.upload')
        ->where('path', '.*');
    Route::get('/list/{path?}', [FileController::class, 'list'])
        ->name('files.list')
        ->where('path', '.*');
    Route::get('/rename/{path?}', [FileController::class, 'rename'])
        ->name('files.rename')
        ->where('path', '.*');

    Route::get('/download/{path?}', [FileController::class, 'downloadMethod'])
        ->name('files.download.method')
        ->where('path', '.*');
    Route::post('/upload/{path?}', [FileController::class, 'uploadMethod'])
        ->name('files.upload.method')
        ->where('path', '.*');
    Route::delete('/delete/{path?}', [FileController::class, 'deleteMethod'])
        ->name('files.delete.method')
        ->where('path', '.*');
    Route::put('/rename/{path?}', [FileController::class, 'renameMethod'])
        ->name('files.rename.method')
        ->where('path', '.*');

    /**
     * DIRECTORIES
     */
    Route::get('/mkdir/{path?}', [FileController::class, 'mkdir'])
        ->name('files.mkdir')
        ->where('path', '.*');

    Route::post('/mkdir/{path?}', [FileController::class, 'mkdirMethod'])
        ->name('files.mkdir.method')
        ->where('path', '.*');
    Route::delete('/rmdir/{path?}', [FileController::class, 'rmdirMethod'])
        ->name('files.rmdir.method')
        ->where('path', '.*');
});