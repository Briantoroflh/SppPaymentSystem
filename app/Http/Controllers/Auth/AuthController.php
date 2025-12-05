<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    public function index()
    {
        return view('Auth.login');
    }

    public function LoginAdmin(Request $request)
    {
        $validated = Validator::validate($request->all(), User::rules());
        $users = User::where('email', $validated['email'])->first();

        try {
            if ($users && Hash::check($validated['password'], $users->password)) {
                // Check if user has Super Admin role
                if (!$users->hasRole('Super Admin')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User ini bukan Super Admin'
                    ], 400);
                }

                Auth::guard('web')->login($users);
                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil sebagai Super Admin!',
                    'data' => $users,
                    'redirect' => '/dashboard'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function Logout(Request $request)
    {
        // Cek role user sebelum logout
        $user = Auth::user();
        $redirectRoute = 'page.student.login'; // Default

        if ($user) {
            // Jika Super Admin atau School Admin, redirect ke login admin
            if ($user->hasRole('Super Admin') || $user->hasRole('School Admin')) {
                $redirectRoute = 'login';
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute)->with('success', 'Logout berhasil');
    }
}
