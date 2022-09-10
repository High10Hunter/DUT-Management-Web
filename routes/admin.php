<?php

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
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/create', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
    Route::put('/edit/{user}', [UserController::class, 'update'])->name('update');
    Route::post('/import_csv', [UserController::class, 'importCSV'])->name('import_csv');
    Route::post('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
});
