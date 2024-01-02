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
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete;
            $table->foreignId('medication_id')->constrained('medications')->cascadeOnDelete;
            $table->integer('quantity')->unsigned()->default(1)->min(1);
            $table->boolean('payment')->default(false);
            $table->enum('status',['Preparing', 'Sent', 'Received'])->default('Received');
            $table->timestamps();
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
