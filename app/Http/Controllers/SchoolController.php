<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class SchoolController extends Controller
{
    public function index()
    {
        return view('school-management');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Name', 'value' => 'name'],
            ['key' => 'Address', 'value' => 'address'],
            ['key' => 'Phone Number', 'value' => 'phone_number'],
            ['key' => 'Level', 'value' => 'level'],
            ['key' => 'Region', 'value' => 'region'],
        ];
    }

    public function getAll(Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? '';

        // Ensure length is positive
        if ($length <= 0 || $length > 1000) {
            $length = 10;
        }

        // Query dasar dengan LEFT JOIN untuk users
        $query = School::join('regions', 'schools.region_id', '=', 'regions.id')
            ->leftJoin('users', 'schools.user_id', '=', 'users.id')
            ->select('schools.id', 'schools.name', 'schools.address', 'schools.phone_number', 'schools.level', 'regions.name as region', 'users.name as user_name');

        // Search filter
        if (!empty($search)) {
            $query->where('schools.name', 'like', "%{$search}%")
                ->orWhere('schools.address', 'like', "%{$search}%")
                ->orWhere('schools.phone_number', 'like', "%{$search}%")
                ->orWhere('schools.level', 'like', "%{$search}%")
                ->orWhere('regions.name', 'like', "%{$search}%")
                ->orWhere('users.name', 'like', "%{$search}%");
        }

        // Total records
        $totalRecords = School::count();
        $filteredRecords = $query->count();

        // Pagination & sorting
        $schools = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $schools
        ]);
    }

    public function LoginSchoolAdmin(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        $validated = Validator::validate($request->all(), $rules);

        try {
            // Find user by email with School Admin role
            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak terdaftar'
                ], 400);
            }

            // Check password
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah'
                ], 400);
            }

            // Check if user has School Admin role
            if (!$user->hasRole('School Admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ini bukan School Admin'
                ], 400);
            }

            // Find school associated with this user
            $school = School::where('user_id', $user->id)->first();

            if (!$school) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun ini tidak terikat dengan sekolah apapun'
                ], 400);
            }

            // Login user
            Auth::login($user);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil sebagai School Admin!',
                'data' => [
                    'user' => $user,
                    'school' => $school
                ],
                'redirect' => '/dashboard'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $validated = Validator::validate($request->all(), School::rules());
        $validated['created_by'] = Auth::user()->name;

        try {
            if ($validated) {
                $school = School::create($validated);

                // If user_id is provided, assign School Admin role to user
                if (!empty($validated['user_id'])) {
                    $user = User::findOrFail($validated['user_id']);
                    // Remove other roles and assign School Admin
                    $user->syncRoles(['School Admin']);
                }
            } else {
                return response()->json(['message' => 'Sepertinya ada yang salah!']);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'School berhasil terbuat!', 'data' => $school]);
    }

    public function getById($id)
    {
        try {
            $school = School::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $school
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'School tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::validate($request->all(), School::rules());
        $validated['created_by'] = Auth::user()->name;

        try {
            $school = School::findOrFail($id);
            $oldUserId = $school->user_id;

            $school->update($validated);

            // Handle user_id changes
            $newUserId = $validated['user_id'] ?? null;

            // If user_id changed, update roles
            if ($oldUserId !== $newUserId) {
                // If old user_id exists, remove School Admin role
                if ($oldUserId) {
                    $oldUser = User::find($oldUserId);
                    if ($oldUser && $oldUser->hasRole('School Admin')) {
                        $oldUser->removeRole('School Admin');
                    }
                }

                // If new user_id exists, assign School Admin role
                if ($newUserId) {
                    $newUser = User::findOrFail($newUserId);
                    $newUser->syncRoles(['School Admin']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'School berhasil diupdate!',
                'data' => $school
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $school = School::findOrFail($id);
        try {
            $school->delete();
            return response()->json(['success' => true, 'message' => 'School berhasil di hapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
