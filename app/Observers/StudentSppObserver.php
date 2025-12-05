<?php

namespace App\Observers;

use App\Models\StudentSpp;
use App\Models\StudentSppTracking;
use Carbon\Carbon;

class StudentSppObserver
{
    /**
     * Handle the StudentSpp "created" event.
     */
    public function created(StudentSpp $studentSpp): void
    {
        $this->createMonthlyBillings($studentSpp);
    }

    /**
     * Handle the StudentSpp "updated" event.
     */
    public function updated(StudentSpp $studentSpp): void
    {
        //
    }

    /**
     * Handle the StudentSpp "deleted" event.
     */
    public function deleted(StudentSpp $studentSpp): void
    {
        //
    }

    /**
     * Handle the StudentSpp "restored" event.
     */
    public function restored(StudentSpp $studentSpp): void
    {
        //
    }

    /**
     * Handle the StudentSpp "force deleted" event.
     */
    public function forceDeleted(StudentSpp $studentSpp): void
    {
        //
    }

    /**
     * Create monthly billing records for 12 months
     */
    private function createMonthlyBillings(StudentSpp $studentSpp): void
    {
        $currentDate = Carbon::now();

        // Create billing records for 12 months starting from current month
        for ($i = 0; $i < 12; $i++) {
            $billingDate = $currentDate->copy()->addMonths($i);

            StudentSppTracking::create([
                'student_spp_id' => $studentSpp->id,
                'date_month' => $billingDate->startOfMonth(),
                'year' => $billingDate->year,
                'status' => 'unpaid', // Default status: unpaid
            ]);
        }
    }
}
