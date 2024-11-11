<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'hora_inicio',
        'hora_fin',
        'dia',
        'salon_id', // Cambié a `salon_id`
    ];

    // Definir la relación con el modelo Salon
    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }
}
