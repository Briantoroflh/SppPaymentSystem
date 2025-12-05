<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MajorController extends Controller
{
    public function index()
    {
        return view('major-management');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Name', 'value' => 'name'],
            ['key' => 'Start At', 'value' => 'start_at'],
            ['key' => 'Active', 'value' => 'isActive'],
        ];
    }

    public function getAll(Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? '';

        // Query dasar
        $query = Major::select('id', 'name', 'start_at', 'isActive');

        // Search filter
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('start_at', 'like', "%{$search}%");
        }

        // Total records
        $totalRecords = Major::count();
        $filteredRecords = $query->count();

        // Pagination & sorting
        $majors = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $majors
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::validate($request->all(), Major::rules());

        try {
            if ($validated) {
                $major = Major::create($validated);
            } else {
                return response()->json(['message' => 'Sepertinya ada yang salah!']);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Major berhasil terbuat!', 'data' => $major]);
    }

    public function getById($id)
    {
        try {
            $major = Major::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $major
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Major tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::validate($request->all(), Major::rules());

        try {
            $major = Major::findOrFail($id);
            $major->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Major berhasil diupdate!',
                'data' => $major
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $major = Major::findOrFail($id);
        try {
            $major->delete();
            return response()->json(['success' => true, 'message' => 'Major berhasil di hapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
