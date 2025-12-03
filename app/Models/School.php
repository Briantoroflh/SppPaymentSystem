<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'level',
        'region_id',
    ];

    // Relationships
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function schoolGroups()
    {
        return $this->hasMany(SchoolGroup::class);
    }
}
