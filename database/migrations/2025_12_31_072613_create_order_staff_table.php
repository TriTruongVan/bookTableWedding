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
        Schema::create('order_staff', function (Blueprint $table) {
            $table->foreignUlid('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->date('event_date');
            $table->enum('session', ['trua', 'chieu']);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['staff_id', 'event_date', 'session']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_staff');
    }
};
