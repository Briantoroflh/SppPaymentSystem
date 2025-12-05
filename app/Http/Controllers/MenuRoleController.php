<?php

namespace App\Http\Controllers;

use App\Models\MenuRole;

class MenuRoleController extends Controller {
    public function getMenuByRole($role) {
        $menu = MenuRole::join('menu', 'menu_role.menu_id', '=', 'menu.id')
                ->join('role', 'menu_role.role_id', '=', 'role.id')
                ->select('menu.title', 'role.name')
                ->where('role.name', $role)
                ->get();

        return response()->json(['data' => $menu]);
    }
}