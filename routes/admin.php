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
});
