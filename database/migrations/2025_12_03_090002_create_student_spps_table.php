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
        Schema::create('student_spps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_class_id')->constrained('student_classes')->onDelete('cascade');
            $table->double('price');
            $table->integer('semester');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_spps');
    }
};
