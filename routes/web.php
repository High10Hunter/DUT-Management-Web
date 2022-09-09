<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [TestController::class, 'test']);

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'logining'])->name('logining');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
