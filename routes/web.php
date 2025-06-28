<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

// Student Management Routes
Route::post('/students', [StudentController::class, 'addStudent']);
Route::get('/students', [StudentController::class, 'listStudents']);
Route::get('/students/{student}', [StudentController::class, 'getStudent']);
Route::delete('/students/{student}', [StudentController::class, 'deleteStudent']);
Route::put('/students/{student}', [StudentController::class, 'updateStudent']);
