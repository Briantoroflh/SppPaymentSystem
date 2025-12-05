<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\StudentSpp;
use App\Models\StudentClass;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SppStudentController extends Controller
{
    public function index()
    {
        return view('School.spp-student-management');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Student Class', 'value' => 'student_class'],
            ['key' => 'Price', 'value' => 'price'],
            ['key' => 'Semester', 'value' => 'semester'],
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

        $query = StudentSpp::join('student_classes', 'student_spps.student_class_id', '=', 'student_classes.id')
            ->join('students', 'student_classes.student_id', '=', 'students.id')
            ->join('classes', 'student_classes.class_id', '=', 'classes.id')
            ->select('student_spps.id', 'students.name', 'classes.name as class', 'student_spps.price', 'student_spps.semester')
            ->selectRaw("CONCAT(students.name, ' - ', classes.name) as student_class");

        if (!empty($search)) {
            $query->where('students.name', 'like', "%{$search}%")
                ->orWhere('classes.name', 'like', "%{$search}%")
                ->orWhere('student_spps.semester', 'like', "%{$search}%");
        }

        $totalRecords = StudentSpp::count();
        $filteredRecords = $query->count();

        $studentSpps = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $studentSpps
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::validate($request->all(), [
            'student_class_id' => 'numeric|required|exists:student_classes,id',
            'price' => 'numeric|required',
            'semester' => 'string|required',
        ]);

        try {
            $studentSpp = StudentSpp::create($validated);
            return response()->json(['success' => true, 'message' => 'Student SPP berhasil terbuat!', 'data' => $studentSpp]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function getById($id)
    {
        try {
            $studentSpp = StudentSpp::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $studentSpp
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student SPP tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::validate($request->all(), [
            'student_class_id' => 'numeric|required|exists:student_classes,id',
            'price' => 'numeric|required',
            'semester' => 'string|required',
        ]);

        try {
            $studentSpp = StudentSpp::findOrFail($id);
            $studentSpp->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Student SPP berhasil diupdate!',
                'data' => $studentSpp
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $studentSpp = StudentSpp::findOrFail($id);
        try {
            $studentSpp->delete();
            return response()->json(['success' => true, 'message' => 'Student SPP berhasil dihapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
