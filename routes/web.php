<?php

use Illuminate\Support\Facades\Route;
use App\Models\Student;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/students', [App\Http\Controllers\StudentController::class, 'addStudent']);

// student list route
Route::get('/students', function () {
    return Student::all();
});
Route::delete('/students/{id}', function ($id) {
    \App\Models\Student::findOrFail($id)->delete();
    return response()->json(['message' => 'Student deleted successfully']);
});
