<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Truck extends Model
{
    protected $fillable = [
        'plate_chuto', 
        'plate_remolque', 
        'transport_company'
    ];

    /**
     * Relación: Un camión puede tener muchas operaciones (viajes) a lo largo del tiempo.
     */
    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    /**
     * Interactuar con la placa del chuto (Siempre en Mayúsculas).
     */
    protected function plateChuto(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtoupper($value),
        );
    }

    /**
     * Helper para obtener la identificación completa del vehículo.
     */
    public function getFullPlatesAttribute()
    {
        return "Chuto: {$this->plate_chuto} | Remolque: " . ($this->plate_remolque ?? 'N/A');
    }
}