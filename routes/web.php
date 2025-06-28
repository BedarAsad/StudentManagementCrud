<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CustomFieldController;

Route::get('/', function () {
    return view('welcome');
});

// Student Management Routes
Route::post('/students', [StudentController::class, 'addStudent']);
Route::get('/students', [StudentController::class, 'listStudents']);
Route::get('/students/{student}', [StudentController::class, 'getStudent']);
Route::delete('/students/{student}', [StudentController::class, 'deleteStudent']);
Route::put('/students/{student}', [StudentController::class, 'updateStudent']);

// Custom Fields Routes
Route::get('/custom-fields', [CustomFieldController::class, 'customFields']);
Route::post('/custom-fields', [CustomFieldController::class, 'addCustomField']);
Route::put('/custom-fields/{customField}', [CustomFieldController::class, 'updateCustomField']);
Route::delete('/custom-fields/{customField}', [CustomFieldController::class, 'deleteCustomField']);
