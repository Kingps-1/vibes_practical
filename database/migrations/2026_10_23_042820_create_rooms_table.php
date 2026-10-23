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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->string('block'); // e.g. Block A (Boys), Block B (Girls), Block C (Scholars)
            $table->string('room_type'); // Single Deluxe, Double Sharing, Triple Sharing
            $table->unsignedTinyInteger('capacity')->default(2);
            $table->unsignedTinyInteger('current_occupancy')->default(0);
            $table->string('status')->default('available'); // available, full, maintenance
            $table->decimal('monthly_fee', 8, 2)->default(5000.00);
            $table->unsignedTinyInteger('floor')->default(1);
            $table->string('amenities')->nullable(); // Wi-Fi, Attached Bath, Study Table, AC, Balcony
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
