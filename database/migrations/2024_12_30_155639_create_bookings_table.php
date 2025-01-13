<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  
            $table->foreignId('flight_id')->constrained('flights')->onDelete('cascade');
            $table->decimal('total_price', 10, 2);  
            $table->enum('payment_status', ['pending', 'completed', 'failed']);  
            $table->string('reference_code')->unique();
            $table->timestamp('booking_date')->useCurrent();
            $table->timestamp('cancellation_date')->nullable();
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
