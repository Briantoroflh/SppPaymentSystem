<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sequence',
        'head_title',
        'title',
        'icon',
        'url',
        'created_by',
    ];
}
