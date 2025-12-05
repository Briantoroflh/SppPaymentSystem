<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index()
    {
        return view('School.teacher-management');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Name', 'value' => 'name'],
            ['key' => 'Title', 'value' => 'title'],
        ];
    }

    public function getAll(Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? '';

        if ($length <= 0 || $length > 1000) {
            $length = 10;
        }

        $query = Teacher::select('id', 'name', 'title');

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%");
        }

        $totalRecords = Teacher::count();
        $filteredRecords = $query->count();

        $teachers = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $teachers
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::validate($request->all(), [
            'name' => 'string|required',
            'title' => 'string|required',
        ]);

        try {
            $teacher = Teacher::create($validated);
            return response()->json(['success' => true, 'message' => 'Teacher berhasil terbuat!', 'data' => $teacher]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function getById($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $teacher
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::validate($request->all(), [
            'name' => 'string|required',
            'title' => 'string|required',
        ]);

        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Teacher berhasil diupdate!',
                'data' => $teacher
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        try {
            $teacher->delete();
            return response()->json(['success' => true, 'message' => 'Teacher berhasil dihapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
