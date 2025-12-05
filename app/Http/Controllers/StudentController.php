<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        return view('student-management');
    }

    public function pageLogin()
    {
        return view('Student.login');
    }

    public function dashboard()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('page.login.student')->with('error', 'Silakan login terlebih dahulu');
        }
        return view('Student.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('page.login.student')->with('success', 'Logout berhasil');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Name', 'value' => 'name'],
            ['key' => 'NISN', 'value' => 'nisn'],
            ['key' => 'Phone', 'value' => 'phone_number'],
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
        $query = Student::select('id', 'name', 'nisn', 'phone_number', 'isActive');

        // Search filter
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%")
                ->orWhere('phone_number', 'like', "%{$search}%");
        }

        // Total records
        $totalRecords = Student::count();
        $filteredRecords = $query->count();

        // Pagination & sorting
        $students = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $students
        ]);
    }

    public function LoginStudent(Request $request)
    {
        $validated = Validator::validate($request->all(), [
            'name' => 'string|required',
            'nisn' => 'string|required'
        ]);

        try {
            $student = Student::where('nisn', $validated['nisn'])->first();

            if ($student) {
                Auth::guard('student')->login($student);
                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil!',
                    'data' => $student
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak terdaftar!'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = Validator::validate($request->all(), Student::rules());

        try {
            if ($validated) {
                $student = Student::create($validated);
            } else {
                return response()->json(['message' => 'Sepertinya ada yang salah!']);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Student berhasil terbuat!', 'data' => $student]);
    }

    public function getById($id)
    {
        try {
            $student = Student::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $student
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::validate($request->all(), Student::rules());

        try {
            $student = Student::findOrFail($id);
            $student->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Student berhasil diupdate!',
                'data' => $student
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        try {
            $student->delete();
            return response()->json(['success' => true, 'message' => 'Student berhasil di hapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
