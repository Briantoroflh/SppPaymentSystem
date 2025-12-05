<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Major;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    public function index()
    {
        return view('School.classes-management');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Name', 'value' => 'name'],
            ['key' => 'Major', 'value' => 'major'],
            ['key' => 'Homeroom Teacher', 'value' => 'teacher'],
            ['key' => 'Total Student', 'value' => 'total_student'],
            ['key' => 'Total Student Paid Done', 'value' => 'total_student_already_paid_spp'],
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

        $query = ClassModel::join('majors', 'classes.major_id', '=', 'majors.id')
            ->leftJoin('teachers', 'classes.homeroom_teacher', '=', 'teachers.id')
            ->select('classes.id', 'classes.name', 'majors.name as major', 'teachers.name as teacher', 'classes.total_student', 'classes.total_student_already_paid_spp');

        if (!empty($search)) {
            $query->where('classes.name', 'like', "%{$search}%")
                ->orWhere('majors.name', 'like', "%{$search}%")
                ->orWhere('teachers.name', 'like', "%{$search}%");
        }

        $totalRecords = ClassModel::count();
        $filteredRecords = $query->count();

        $classes = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $classes
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::validate($request->all(), [
            'name' => 'string|required',
            'major_id' => 'numeric|required|exists:majors,id',
            'homeroom_teacher' => 'nullable|numeric|exists:teachers,id',
        ]);

        try {
            $class = ClassModel::create($validated);
            return response()->json(['success' => true, 'message' => 'Class berhasil terbuat!', 'data' => $class]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function getById($id)
    {
        try {
            $class = ClassModel::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $class
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Class tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::validate($request->all(), [
            'name' => 'string|required',
            'major_id' => 'numeric|required|exists:majors,id',
            'homeroom_teacher' => 'nullable|numeric|exists:teachers,id',
        ]);

        try {
            $class = ClassModel::findOrFail($id);
            $class->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Class berhasil diupdate!',
                'data' => $class
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);
        try {
            $class->delete();
            return response()->json(['success' => true, 'message' => 'Class berhasil dihapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
