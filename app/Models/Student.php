<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'email',
        'phone',
        'address',
        'photo',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_relation',
        'class_id',
        'section_id',
        'enrollment_date',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
    ];

    // Relationships
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Boot method for auto-generating student ID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->student_id)) {
                $student->student_id = 'STU' . date('Y') . str_pad(Student::count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}