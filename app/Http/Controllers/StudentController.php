<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function addStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ]);

        $student = Student::create($validated);

        return response()->json([
            'message' => 'Student added successfully',
            'student' => $student,
        ], 201);
    }
}