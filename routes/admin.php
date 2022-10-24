<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EAOStaffController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\StudentController;
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
    Route::get('/create_eao_staff', [EAOStaffController::class, 'create'])->name('create_eao_staff');
    Route::post('/create_eao_staff', [EAOStaffController::class, 'store'])->name('store_eao_staff');
    Route::post('/reset/{user}', [UserController::class, 'resetAccount'])->name('reset');
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
    Route::post('/import_csv', [SubjectController::class, 'importCSV'])->name('import_csv');
    Route::get('/export_sample_csv', [SubjectController::class, 'exportSampleCSV'])->name('export_sample_csv');
});


Route::group([
    'prefix' => 'majors',
    'as' => 'majors.',
], static function () {
    Route::get('/', [MajorController::class, 'index'])->name('index');
    Route::post('/import_csv', [MajorController::class, 'importCSV'])->name('import_csv');
    Route::get('/export_sample_csv', [MajorController::class, 'exportSampleCSV'])->name('export_sample_csv');
    Route::get('/edit/{major}', [MajorController::class, 'edit'])->name('edit');
    Route::put('/edit/{major}', [MajorController::class, 'update'])->name('update');
});

Route::group([
    'prefix' => 'students',
    'as' => 'students.',
], static function () {
    Route::get('/', [StudentController::class, 'index'])->name('index');
    Route::post('/import_csv', [StudentController::class, 'importCSV'])->name('import_csv');
    Route::get('/export_sample_csv', [StudentController::class, 'exportSampleCSV'])->name('export_sample_csv');
    Route::post('/export_csv', [StudentController::class, 'exportCSV'])->name('export_csv');
    Route::get('/edit/{student}', [StudentController::class, 'edit'])->name('edit');
    Route::put('/edit/{student}', [StudentController::class, 'update'])->name('update');
    Route::post('/destroy/{student}', [StudentController::class, 'destroy'])->name('destroy');
});

Route::group(
    [
        'prefix' => 'modules',
        'as' => 'modules.',
    ],
    static function () {
        Route::get('/', [ModuleController::class, 'index'])->name('index');
        Route::post('/import_csv', [ModuleController::class, 'importCSV'])->name('import_csv');
        Route::post('/import_student_list_csv', [ModuleController::class, 'importStudentListCSV'])->name('import_student_list_csv');
        Route::get('/export_sample_csv', [ModuleController::class, 'exportSampleCSV'])->name('export_sample_csv');
        Route::get('/export_sample_student_list_csv', [ModuleController::class, 'exportSampleStudentListCSV'])->name('export_sample_student_list_csv');
        Route::post('/get_lecturers', [ModuleController::class, 'getLecturers'])->name('get_lecturers');
        Route::post('/get_students', [ModuleController::class, 'getStudentsInModule'])->name('get_students');
        Route::post('/create', [ModuleController::class, 'store'])->name('store');
        Route::get('/edit/{module}', [ModuleController::class, 'edit'])->name('edit');
        Route::put('/edit/{module}', [ModuleController::class, 'update'])->name('update');
    }
);

Route::group(
    [
        'prefix' => 'lecturers',
        'as' => 'lecturers.',
    ],
    static function () {
        Route::get('/', [LecturerController::class, 'index'])->name('index');
        Route::get('/create', [LecturerController::class, 'create'])->name('create');
        Route::post('/create', [LecturerController::class, 'store'])->name('store');
        Route::get('/edit/{lecturer}', [LecturerController::class, 'edit'])->name('edit');
        Route::put('/edit/{lecturer}', [LecturerController::class, 'update'])->name('update');
    }
);

Route::group(
    [
        'prefix' => 'exams',
        'as' => 'exams.',
    ],
    static function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::get('/create', [ExamController::class, 'create'])->name('create');
        Route::post('/create', [ExamController::class, 'store'])->name('store');
        Route::get('/getExams', [ExamController::class, 'getExams'])->name('get_exams');
        Route::post('/get_students', [ExamController::class, 'getStudentsInExam'])->name('get_students');
        Route::post('/export_csv', [ExamController::class, 'exportCSV'])->name('export_csv');
    }
);
