<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Authenticatable;

abstract class Controller
{
    use Authenticatable;
}
