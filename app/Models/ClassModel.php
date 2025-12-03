<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'major_id',
        'homeroom_teacher',
        'total_student',
        'total_student_already_paid_spp',
    ];

    // Relationships
    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher');
    }

    public function studentClasses()
    {
        return $this->hasMany(StudentClass::class, 'class_id');
    }
}
