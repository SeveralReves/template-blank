<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;
    protected $fillable = [
        'vessel_id', 'truck_id', 'driver_id', 'bl_number', 
        'protection_ticket', 'destination', 'weight_tara', 
        'weight_full', 'current_status', 'started_at', 'finished_at'
    ];

    // Relaciones
    public function vessel() { return $this->belongsTo(Vessel::class); }
    public function truck() { return $this->belongsTo(Truck::class); }
    public function driver() { return $this->belongsTo(Driver::class); }
    public function logs() { return $this->hasMany(OperationStatusLog::class); }

    // Scope para filtrar las que están actualmente en el puerto
    public function scopeInPort($query)
    {
        return $query->where('current_status', '!=', 'salida');
    }
}
