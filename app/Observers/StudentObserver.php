<?php

namespace App\Observers;

use App\Models\Student;
use Spatie\Permission\Models\Role;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        try {
            // Cari role 'Student' dengan guard 'web'
            $role = Role::where('name', 'Student')
                ->where('guard_name', 'student')
                ->first();

            // Jika role ada, assign ke student
            if ($role && !$student->hasRole('Student')) {
                $student->assignRole($role);
            }
        } catch (\Exception $e) {
            // Log error jika role assignment gagal
            \Log::error('Failed to assign Student role: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        //
    }
}
