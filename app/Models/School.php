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
        'user_id',
    ];

    public static function rules()
    {
        return [
            'name' => 'string|required',
            'address' => 'string|required',
            'phone_number' => 'string|required',
            'level' => 'string|required',
            'region_id' => 'numeric|required',
            'user_id' => 'nullable|numeric|exists:users,id',
        ];
    }

    // Relationships
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function schoolGroups()
    {
        return $this->hasMany(SchoolGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
