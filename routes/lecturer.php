<?php

use App\Http\Controllers\PeriodController;
use Illuminate\Support\Facades\Route;

// Route::get('/', [PeriodController::class, 'form'])->name('index');
Route::get('/', [PeriodController::class, 'index'])->name('index');

Route::group([
    'prefix' => 'periods',
    'as' => 'periods.',
], static function () {
    Route::any('/form/{moduleId?}', [PeriodController::class, 'form'])->name('form');
    Route::post('/attendance', [PeriodController::class, 'attendance'])->name('attendance');
});
