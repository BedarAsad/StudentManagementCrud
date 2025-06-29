<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Student;
use App\Models\CustomField;
use App\Models\StudentCustomFieldValue;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_add_a_student_with_custom_fields()
    {
        $field = CustomField::create([
            'name' => 'Address',
            'data_type' => 'text',
            'required' => true,
        ]);

        $response = $this->postJson('/students', [
            'name' => 'Test Student',
            'contact_number' => '1234567890',
            'custom_fields' => [
                $field->id => '123 Main St',
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Test Student']);

        $this->assertDatabaseHas('students', ['name' => 'Test Student']);
        $this->assertDatabaseHas('student_custom_field_values', [
            'custom_field_id' => $field->id,
            'value' => '123 Main St',
        ]);
    }

    /** @test */
    public function it_can_update_a_student_and_custom_fields()
    {
        $student = Student::create(['name' => 'Old Name', 'contact_number' => '111']);
        $field = CustomField::create(['name' => 'Age', 'data_type' => 'number', 'required' => false]);
        StudentCustomFieldValue::create([
            'student_id' => $student->id,
            'custom_field_id' => $field->id,
            'value' => '20',
        ]);

        $response = $this->putJson("/students/{$student->id}", [
            'name' => 'New Name',
            'contact_number' => '222',
            'custom_fields' => [
                $field->id => '21',
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Student updated successfully']);

        $this->assertDatabaseHas('students', ['name' => 'New Name', 'contact_number' => '222']);
        $this->assertDatabaseHas('student_custom_field_values', [
            'student_id' => $student->id,
            'custom_field_id' => $field->id,
            'value' => '21',
        ]);
    }

    /** @test */
    public function it_can_list_students_with_custom_fields()
    {
        $field = CustomField::create(['name' => 'Parent', 'data_type' => 'text', 'required' => false]);
        $student = Student::create(['name' => 'Alice', 'contact_number' => '555']);
        StudentCustomFieldValue::create([
            'student_id' => $student->id,
            'custom_field_id' => $field->id,
            'value' => 'Bob',
        ]);

        $response = $this->getJson('/students');
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Alice'])
            ->assertJsonFragment(['custom_fields' => [$field->id => 'Bob']]);
    }

    /** @test */
    public function it_can_delete_a_student_and_custom_fields()
    {
        $student = Student::create(['name' => 'Delete Me', 'contact_number' => '000']);
        $field = CustomField::create(['name' => 'Test', 'data_type' => 'text', 'required' => false]);
        StudentCustomFieldValue::create([
            'student_id' => $student->id,
            'custom_field_id' => $field->id,
            'value' => 'Some Value',
        ]);

        $response = $this->deleteJson("/students/{$student->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Student deleted successfully']);

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
        $this->assertDatabaseMissing('student_custom_field_values', [
            'student_id' => $student->id,
        ]);
    }
}
