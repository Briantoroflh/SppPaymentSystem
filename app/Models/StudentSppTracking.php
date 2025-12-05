<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSppTracking extends Model
{
    use SoftDeletes;

    protected $table = 'student_spp_trackings';
    protected $fillable = [
        'student_spp_id',
        'date_month',
        'year',
        'status',
    ];

    protected $casts = [
        'date_month' => 'date',
    ];

    // Relationships
    public function studentSpp()
    {
        return $this->belongsTo(StudentSpp::class);
    }
}
