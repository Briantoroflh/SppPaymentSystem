<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_id',
        'student_spp_tracking_id',
        'total_price',
        'payment_method',
        'status_payment',
    ];

    public $timestamps = false;

    protected $casts = [
        'total_price' => 'double',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function studentSpp()
    {
        return $this->belongsTo(StudentSpp::class);
    }

    public function studentSppTracking()
    {
        return $this->belongsTo(StudentSppTracking::class);
    }
}
