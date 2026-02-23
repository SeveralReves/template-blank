<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'dock_number', 'product', 'target_tonnage', 'status'];

    // Relación con todas las operaciones (gandolas) vinculadas al buque
    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    // Atributo para calcular el progreso real en el Dashboard
    public function getProgressPercentageAttribute()
    {
        $totalDownloaded = $this->operations()->sum('weight_net');
        return $this->target_tonnage > 0 ? ($totalDownloaded / $this->target_tonnage) * 100 : 0;
    }
}
