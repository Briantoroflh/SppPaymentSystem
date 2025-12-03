<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Major extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'start_at',
        'isActive',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'isActive' => 'boolean',
    ];

    // Relationships
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }
}
