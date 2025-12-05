<?php

namespace App\Helper;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuAccessHelper
{
    /**
     * Get accessible menus for current user based on roles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAccessibleMenus()
    {
        $user = Auth::user();

        // If user is not authenticated, return empty collection
        if (!$user) {
            return collect();
        }

        // Get user roles
        $userRoles = $user->getRoleNames()->toArray();

        // If user has Super Admin role, show all menus
        // if (in_array('Super Admin', $userRoles)) {
        //     return Menu::orderBy('sequence')->get();
        // }

        // For other roles, filter menus by role access
        return Menu::whereHas('roles', function ($query) use ($userRoles) {
            $query->whereIn('name', $userRoles);
        })
            ->orderBy('sequence')
            ->get();
    }

    /**
     * Check if user can access a specific menu
     *
     * @param int $menuId
     * @return bool
     */
    public static function canAccessMenu($menuId)
    {
        $user = Auth::user();

        // If user is not authenticated, deny access
        if (!$user) {
            return false;
        }

        // Super Admin can access all menus
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Check if menu is assigned to user's roles
        $userRoles = $user->getRoleNames()->toArray();

        return Menu::where('id', $menuId)
            ->whereHas('roles', function ($query) use ($userRoles) {
                $query->whereIn('name', $userRoles);
            })
            ->exists();
    }

    /**
     * Get menus grouped by section for current user
     *
     * @return array
     */
    public static function getGroupedAccessibleMenus()
    {
        return self::getAccessibleMenus()->groupBy('head_title');
    }
}
