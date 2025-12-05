<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable
{
    use SoftDeletes, HasRoles;

    /**
     * Specify guard untuk Spatie Permission dan Authentication
     */
    protected $guard_name = 'student';

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

    /**
     * Override guard name untuk Spatie Permission
     */
    public function getGuardName()
    {
        return $this->guard_name ?? 'student';
    }

    protected $casts = [
        'isActive' => 'boolean',
    ];

    public static function rules()
    {
        return [
            'name' => 'string|required',
            'age' => 'numeric|required',
            'nisn' => 'string|required',
            'nik' => 'string|required',
            'phone_number' => 'string|required',
            'isActive' => 'boolean|required',
        ];
    }

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
