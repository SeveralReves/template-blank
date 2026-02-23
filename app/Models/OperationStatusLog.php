<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationStatusLog extends Model
{
    use HasFactory;
    
    public $timestamps = false; // Usamos changed_at manualmente
    protected $fillable = ['operation_id', 'status', 'user_id', 'changed_at'];

    public function user() { return $this->belongsTo(User::class); }
}
