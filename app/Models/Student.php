<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'age',
        'nisn',
        'nik',
        'phone_number',
        'isActive',
        'remaining_payment',
        'already_paid',
    ];

    protected $casts = [
        'isActive' => 'boolean',
    ];

    // Relationships
    public function schoolGroups()
    {
        return $this->hasMany(SchoolGroup::class);
    }

    public function studentClasses()
    {
        return $this->hasMany(StudentClass::class);
    }
}
