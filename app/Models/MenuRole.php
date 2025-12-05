<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

class MenuRole extends Model
{
    protected $table = 'menu_role';

    protected $fillable = [
        'menu_id',
        'role_id',
    ];

    /**
     * Get the menu associated with this menu-role relationship.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Get the role associated with this menu-role relationship.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
