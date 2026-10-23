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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('student_id_number')->unique(); // e.g. STU-2026-001
            $table->string('full_name');
            $table->string('gender')->default('Male'); // Male, Female, Other
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('course'); // B.Tech CSE, BCA, MCA, MBA, etc.
            $table->unsignedTinyInteger('semester')->default(1);
            $table->string('guardian_name');
            $table->string('guardian_phone');
            $table->text('address')->nullable();
            $table->string('blood_group')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
