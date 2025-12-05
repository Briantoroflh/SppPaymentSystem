<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('users-management');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Name', 'value' => 'name'],
            ['key' => 'Email', 'value' => 'email'],
        ];
    }

    public function getAll(Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? '';

        // Query dasar
        $query = User::select('id', 'name', 'email');

        // Search filter
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        // Total records
        $totalRecords = User::count();
        $filteredRecords = $query->count();

        // Pagination & sorting
        $users = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'string|required',
            'email' => 'email|required|unique:users',
            'password' => 'string|required|min:6',
        ];

        $validated = Validator::validate($request->all(), $rules);
        $validated['password'] = Hash::make($validated['password']);

        try {
            if ($validated) {
                $user = User::create($validated);
            } else {
                return response()->json(['message' => 'Sepertinya ada yang salah!']);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'User berhasil terbuat!', 'data' => $user]);
    }

    public function getById($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email,' . $id,
            'password' => 'string|nullable|min:6',
        ];

        $validated = Validator::validate($request->all(), $rules);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        try {
            $user = User::findOrFail($id);
            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diupdate!',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        try {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'User berhasil di hapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
