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
        Schema::create('orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->date('event_date');
            $table->unsignedTinyInteger('lunar_day')->nullable();
            $table->unsignedTinyInteger('lunar_month')->nullable();
            $table->unsignedSmallInteger('lunar_year')->nullable();
            $table->boolean('isLeapMonth')->default(false);
            $table->string('lunar_can_chi', 20)->nullable();
            $table->enum('session', ['trua', 'chieu']);
            $table->unsignedInteger('table_count')->default(0);
            $table->decimal('deposit_amount', 15, 2)->default(0);
            $table->decimal('price_per_table', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('note')->nullable();
            $table->enum('status', ['san_sang', 'ket_thuc'])->default('san_sang');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['event_date', 'session']);
            $table->index('customer_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
