<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
})->name('index');

Route::group([
    'prefix' => 'users',
    'as' => 'users.',
], static function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'createEAOStaff'])->name('create_eao_staff');
    Route::post('/create', [UserController::class, 'storeEAOStaff'])->name('store_eao_staff');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
    Route::put('/edit/{user}', [UserController::class, 'update'])->name('update');
    Route::post('/import_csv', [UserController::class, 'importCSV'])->name('import_csv');
    Route::post('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
});

Route::group([
    'prefix' => 'courses',
    'as' => 'courses.',
], static function () {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::get('/create', [CourseController::class, 'create'])->name('create');
    Route::post('/create', [CourseController::class, 'store'])->name('store');
    Route::get('/edit/{course}', [CourseController::class, 'edit'])->name('edit');
    Route::put('/update/{course}', [CourseController::class, 'update'])->name('update');
});

Route::group([
    'prefix' => 'subjects',
    'as' => 'subjects.',
], static function () {
    Route::get('/', [SubjectController::class, 'index'])->name('index');
});


Route::group([
    'prefix' => 'majors',
    'as' => 'majors.',
], static function () {
    Route::get('/', [MajorController::class, 'index'])->name('index');
    Route::post('/import_csv', [MajorController::class, 'importCSV'])->name('import_csv');
    Route::get('/edit/{major}', [MajorController::class, 'edit'])->name('edit');
    Route::put('/edit/{major}', [MajorController::class, 'update'])->name('update');
});
