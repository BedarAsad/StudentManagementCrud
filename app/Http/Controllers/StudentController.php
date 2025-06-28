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

    // List all students
    public function listStudents()
    {
        return Student::all();
    }

    // Get a single student using route model binding
    public function getStudent(Student $student)
    {
        return $student;
    }

    // Update a student using route model binding
    public function updateStudent(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ]);
        $student->update($validated);
        return response()->json(['message' => 'Student updated successfully']);
    }

    // Delete a student using route model binding
    public function deleteStudent(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }
}