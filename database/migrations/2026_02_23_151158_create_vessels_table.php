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
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: "Alberto Topic"
            $table->string('dock_number'); // Ej: "#31"
            $table->string('product'); // Ej: "Arroz Paddy"
            $table->decimal('target_tonnage', 15, 2); // Meta total a descargar
            $table->enum('status', ['incoming', 'active', 'finished'])->default('incoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
