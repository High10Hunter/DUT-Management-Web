<?php

use App\Http\Controllers\StudyScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StudyScheduleController::class, 'index'])->name('index');

Route::group([
    'prefix' => 'learning_schedule',
    'as' => 'learning_schedule.',
], static function () {
    Route::get('/get_schedules', [StudyScheduleController::class, 'getSchedules'])
        ->name('get_schedules');
    Route::get('/history', [StudyScheduleController::class, 'history'])->name('history');
    Route::any('/history_attendance/{module?}', [StudyScheduleController::class, 'historyAttendanceOfOneStudent'])
        ->name('history_attendance');
});
