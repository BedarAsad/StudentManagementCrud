<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function customFields()
    {
        return CustomField::all();
    }

    public function addCustomField(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'data_type' => 'required|string|max:50',
            'required' => 'sometimes|boolean',
        ]);
        $field = CustomField::create([
            'name' => $validated['name'],
            'data_type' => $validated['data_type'],
            'required' => $request->boolean('required'),
        ]);
        return response()->json($field, 201);
    }

    public function updateCustomField(Request $request, CustomField $customField)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'data_type' => 'required|string|max:50',
            'required' => 'sometimes|boolean',
        ]);
        $customField->update([
            'name' => $validated['name'],
            'data_type' => $validated['data_type'],
            'required' => $request->boolean('required'),
        ]);
        return response()->json($customField);
    }

    public function deleteCustomField(CustomField $customField)
    {
        $customField->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
