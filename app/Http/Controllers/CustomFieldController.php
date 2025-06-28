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
        ]);
        $field = CustomField::create($validated);
        return response()->json($field, 201);
    }

    public function updateCustomField(Request $request, CustomField $customField)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'data_type' => 'required|string|max:50',
        ]);
        $customField->update($validated);
        return response()->json($customField);
    }

    public function deleteCustomField(CustomField $customField)
    {
        $customField->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
