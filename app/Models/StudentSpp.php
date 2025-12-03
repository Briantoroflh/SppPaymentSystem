<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSpp extends Model
{
    use SoftDeletes;

    protected $table = 'student_spps';

    protected $fillable = [
        'student_class_id',
        'price',
        'semester',
    ];

    protected $casts = [
        'price' => 'double',
    ];

    // Relationships
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function studentSppTrackings()
    {
        return $this->hasMany(StudentSppTracking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
