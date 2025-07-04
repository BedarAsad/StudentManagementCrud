<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'data_type', 'required'];

    public function studentValues()
    {
        return $this->hasMany(StudentCustomFieldValue::class);
    }
}
