<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckMenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If not authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Get current path
        $currentPath = $request->path();

        // Super Admin can access all routes
        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        // Check if menu exists for current path
        $menu = \App\Models\Menu::where('url', '/' . $currentPath)
            ->orWhere('url', $currentPath)
            ->first();

        // If menu doesn't exist, allow access (might be a non-menu route)
        if (!$menu) {
            return $next($request);
        }

        // Check if user has role that can access this menu
        $userRoles = $user->getRoleNames()->toArray();
        $hasAccess = $menu->roles()
            ->whereIn('name', $userRoles)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized to access this menu');
        }

        return $next($request);
    }
}
