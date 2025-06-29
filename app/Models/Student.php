<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_number',
    ];

    public function customFieldValues()
    {
        return $this->hasMany(StudentCustomFieldValue::class);
    }
}