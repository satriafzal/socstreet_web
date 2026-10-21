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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id_string')->unique(); // e.g., INV-001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('book_date');
            $table->string('start_time', 10);
            $table->string('end_time', 10);
            $table->enum('status', ['pending_payment', 'confirmed', 'cancelled'])->default('pending_payment');
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

