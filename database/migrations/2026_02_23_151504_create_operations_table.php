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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained();
            $table->foreignId('truck_id')->constrained();
            $table->foreignId('driver_id')->constrained();
            
            // Datos específicos de la vuelta actual (de tus fotos)
            $table->string('bl_number')->nullable(); // Bill of Lading
            $table->string('protection_ticket')->nullable(); // Ticket protección portuaria
            $table->string('destination')->nullable();
            
            // Pesaje
            $table->decimal('weight_tara', 10, 2)->nullable();
            $table->decimal('weight_full', 10, 2)->nullable();
            $table->decimal('weight_net', 10, 2)->virtualAs('weight_full - weight_tara');
            
            // Control de flujo
            $table->enum('current_status', ['patio', 'costado', 'mesa_tecnica', 'salida']);
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
