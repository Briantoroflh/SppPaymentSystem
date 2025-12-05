<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'longitude',
        'latitude',
    ];

    public static function rules()
    {
        return [
            'name' => 'string|required',
            'longitude' => 'numeric|required',
            'latitude' => 'numeric|required',
        ];
    }

    // Relationships
    public function schools()
    {
        return $this->hasMany(School::class);
    }
}
