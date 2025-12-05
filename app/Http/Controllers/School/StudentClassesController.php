<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\StudentClass;
use App\Models\Student;
use App\Models\ClassModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentClassesController extends Controller
{
    public function index()
    {
        return view('School.student-classes-management');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Student', 'value' => 'student'],
            ['key' => 'Class', 'value' => 'class'],
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

        $query = StudentClass::join('students', 'student_classes.student_id', '=', 'students.id')
            ->join('classes', 'student_classes.class_id', '=', 'classes.id')
            ->select('student_classes.id', 'students.name as student', 'classes.name as class');

        if (!empty($search)) {
            $query->where('students.name', 'like', "%{$search}%")
                ->orWhere('classes.name', 'like', "%{$search}%");
        }

        $totalRecords = StudentClass::count();
        $filteredRecords = $query->count();

        $studentClasses = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $studentClasses
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::validate($request->all(), [
            'student_id' => 'numeric|required|exists:students,id',
            'class_id' => 'numeric|required|exists:classes,id',
        ]);

        try {
            $studentClass = StudentClass::create($validated);
            return response()->json(['success' => true, 'message' => 'Student Class berhasil terbuat!', 'data' => $studentClass]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function getById($id)
    {
        try {
            $studentClass = StudentClass::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $studentClass
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student Class tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::validate($request->all(), [
            'student_id' => 'numeric|required|exists:students,id',
            'class_id' => 'numeric|required|exists:classes,id',
        ]);

        try {
            $studentClass = StudentClass::findOrFail($id);
            $studentClass->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Student Class berhasil diupdate!',
                'data' => $studentClass
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $studentClass = StudentClass::findOrFail($id);
        try {
            $studentClass->delete();
            return response()->json(['success' => true, 'message' => 'Student Class berhasil dihapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
