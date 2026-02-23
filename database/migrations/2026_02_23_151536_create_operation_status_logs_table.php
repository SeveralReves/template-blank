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
        Schema::create('operation_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained()->onDelete('cascade');
            $table->string('status'); // patio, costado, etc.
            $table->foreignId('user_id')->constrained(); // Quién hizo el movimiento (Operador)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_status_logs');
    }
};
