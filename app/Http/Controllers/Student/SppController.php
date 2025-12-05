<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentSppTracking;

class SppController extends Controller {
    public function index() {
        return view('Student.spp.spp');
    }

    public function getAllById($id) {
        $spp = StudentSppTracking::join('student_spps', 'student_spp_trackings.student_spp_id', '=', 'student_spps.id')
                ->join('student_classes', 'student_spps.student_class_id', '=', 'student_classes.id')
                ->join('students', 'student_classes.student_id', '=', 'students.id')
                ->select('student_spp_trackings.id','students.name', 'student_spps.price', 'student_spps.semester', 'student_spp_trackings.date_month', 'student_spp_trackings.status')
                ->where('students.id', $id)
                ->get();

        return response()->json($spp);
    }

    public function getSppById($id) {
        $spp = StudentSppTracking::join('student_spps', 'student_spp_trackings.student_spp_id', '=', 'student_spps.id')
            ->join('student_classes', 'student_spps.student_class_id', '=', 'student_classes.id')
            ->join('students', 'student_classes.student_id', '=', 'students.id')
            ->select('students.name', 'student_spps.price', 'student_spps.semester', 'student_spp_trackings.date_month', 'student_spp_trackings.status')
            ->where('students.id', $id)
            ->first();

        return response()->json($spp);
    }
}