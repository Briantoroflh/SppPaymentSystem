<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    public static function rules()
    {
        return [
            'sequence' => 'numeric|required',
            'head_title' => 'string|required',
            'title' => 'string|required',
            'icon' => 'string|required',
            'url' => 'string|required',
        ];
    }

    protected $fillable = [
        'sequence',
        'head_title',
        'title',
        'icon',
        'url',
        'created_by',
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(\Spatie\Permission\Models\Role::class, 'menu_role', 'menu_id', 'role_id');
    }
}
