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
        Schema::create('order_dishes', function (Blueprint $table) {
            $table->foreignUlid('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('dish_id')->constrained('dishes')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['order_id', 'dish_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_dishes');
    }
};
