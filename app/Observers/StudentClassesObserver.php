<?php

namespace App\Observers;

use App\Models\ClassModel;
use App\Models\StudentClass;

class StudentClassesObserver
{
    /**
     * Handle the StudentClass "created" event.
     */
    public function created(StudentClass $studentClass): void
    {
        $this->updateClassStudentCount($studentClass);
    }

    /**
     * Handle the StudentClass "updated" event.
     */
    public function updated(StudentClass $studentClass): void
    {
        // If class_id changed, update both old and new class
        if ($studentClass->isDirty('class_id')) {
            $oldClassId = $studentClass->getOriginal('class_id');
            if ($oldClassId) {
                $this->updateStudentCountForClass($oldClassId);
            }
        }
        $this->updateClassStudentCount($studentClass);
    }

    /**
     * Handle the StudentClass "deleted" event.
     */
    public function deleted(StudentClass $studentClass): void
    {
        $this->updateClassStudentCount($studentClass);
    }

    /**
     * Handle the StudentClass "restored" event.
     */
    public function restored(StudentClass $studentClass): void
    {
        //
    }

    /**
     * Handle the StudentClass "force deleted" event.
     */
    public function forceDeleted(StudentClass $studentClass): void
    {
        $this->updateClassStudentCount($studentClass);
    }

    /**
     * Update the total_student count for the class
     */
    private function updateClassStudentCount(StudentClass $studentClass): void
    {
        $this->updateStudentCountForClass($studentClass->class_id);
    }

    /**
     * Helper method to update student count for a specific class
     */
    private function updateStudentCountForClass($classId): void
    {
        if (!$classId) {
            return;
        }

        $class = ClassModel::find($classId);
        if ($class) {
            // Count total students in this class
            $totalStudents = StudentClass::where('class_id', $classId)
                ->whereNull('deleted_at')
                ->count();

            // Update the class with the new count
            $class->update(['total_student' => $totalStudents]);
        }
    }
}
