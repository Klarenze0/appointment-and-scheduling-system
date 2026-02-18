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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users');
            $table->foreignId('staff_profile_id')->constrained('staff_profiles');
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('booked_by')->constrained('users');
            $table->dateTime('scheduled_at');
            $table->dateTime('end_at');
            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
                'no_show'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
