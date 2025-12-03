<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_spp_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_spp_id')->constrained('student_spps')->onDelete('cascade');
            $table->date('date_month');
            $table->integer('year');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_spp_trackings');
    }
};
