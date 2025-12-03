<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'title',
    ];

    // Relationships
    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'homeroom_teacher');
    }
}
