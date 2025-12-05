<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('role-management');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Name', 'value' => 'name'],
            ['key' => 'Guard', 'value' => 'guard_name'],
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

        // Query dasar
        $query = Role::select('id', 'name', 'guard_name');

        // Search filter
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('guard_name', 'like', "%{$search}%");
        }

        // Total records
        $totalRecords = Role::count();
        $filteredRecords = $query->count();

        // Pagination & sorting
        $roles = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'string|required|unique:roles,name',
            'guard_name' => 'string|required',
        ];

        $validated = Validator::validate($request->all(), $rules);

        try {
            if ($validated) {
                $role = Role::create($validated);
            } else {
                return response()->json(['message' => 'Sepertinya ada yang salah!']);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Role berhasil terbuat!', 'data' => $role]);
    }

    public function getById($id)
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $role
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string|required|unique:roles,name,' . $id,
            'guard_name' => 'string|required',
        ];

        $validated = Validator::validate($request->all(), $rules);

        try {
            $role = Role::findOrFail($id);
            $role->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diupdate!',
                'data' => $role
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        try {
            $role->delete();
            return response()->json(['success' => true, 'message' => 'Role berhasil di hapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
