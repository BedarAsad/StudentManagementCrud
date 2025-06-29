<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\CustomField;
use App\Models\StudentCustomFieldValue;

class StudentController extends Controller
{
    // Add a new student with custom field values
    public function addStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'custom_fields' => 'array',
            'custom_fields.*' => 'nullable|string|max:255',
        ]);

        $student = Student::create([
            'name' => $validated['name'],
            'contact_number' => $validated['contact_number'],
        ]);

        // Save custom field values
        if (!empty($validated['custom_fields'])) {
            foreach ($validated['custom_fields'] as $fieldId => $value) {
                StudentCustomFieldValue::create([
                    'student_id' => $student->id,
                    'custom_field_id' => $fieldId,
                    'value' => $value,
                ]);
            }
        }

        return response()->json([
            'message' => 'Student added successfully',
            'student' => $student,
        ], 201);
    }

    // List all students with their custom field values
    public function listStudents()
    {
        $students = Student::with(['customFieldValues.customField'])->get();

        $students = $students->map(function ($student) {
            $customFields = [];
            foreach ($student->customFieldValues as $cfv) {
                $customFields[$cfv->custom_field_id] = $cfv->value;
            }
            return [
                'id' => $student->id,
                'name' => $student->name,
                'contact_number' => $student->contact_number,
                'custom_fields' => $customFields,
            ];
        });

        return response()->json($students);
    }

    // Get a single student with custom field values
    public function getStudent(Student $student)
    {
        $student->load('customFieldValues.customField');
        $customFields = [];
        foreach ($student->customFieldValues as $cfv) {
            $customFields[$cfv->custom_field_id] = $cfv->value;
        }
        return response()->json([
            'id' => $student->id,
            'name' => $student->name,
            'contact_number' => $student->contact_number,
            'custom_fields' => $customFields,
        ]);
    }

    // Update a student and their custom field values
    public function updateStudent(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'custom_fields' => 'array',
            'custom_fields.*' => 'nullable|string|max:255',
        ]);

        $student->update([
            'name' => $validated['name'],
            'contact_number' => $validated['contact_number'],
        ]);

        // Update custom field values
        if (isset($validated['custom_fields'])) {
            foreach ($validated['custom_fields'] as $fieldId => $value) {
                StudentCustomFieldValue::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'custom_field_id' => $fieldId,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }

        return response()->json(['message' => 'Student updated successfully']);
    }

    // Delete a student and their custom field values
    public function deleteStudent(Student $student)
    {
        $student->customFieldValues()->delete();
        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }
}