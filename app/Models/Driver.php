<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name', 
        'dni'
    ];

    /**
     * Relación: Un conductor realiza múltiples operaciones de carga.
     */
    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    /**
     * Scope para buscar rápidamente por cédula.
     */
    public function scopeByDni($query, $dni)
    {
        return $query->where('dni', $dni);
    }
}