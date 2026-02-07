<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function classTeacher()
    {
        return $this->belongsTo(User::class, 'class_teacher_id');
    }
    
    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class, 'class_id');
    }
}
