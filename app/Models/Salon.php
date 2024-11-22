<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    use HasFactory;

    // Especificar la tabla y la clave primaria
    protected $table = 'salones';
    protected $primaryKey = 'idSalon';
    
    // Asegurarse de que estos campos puedan ser asignados masivamente
    protected $fillable = ['nombre', 'idPiso'];

    // Relación con Piso (Un salón pertenece a un piso)
    public function piso()
    {
        return $this->belongsTo(Piso::class, 'idPiso'); // Clave foránea: idPiso
    }

    // Relación con Horario (Un salón tiene muchos horarios)
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'idSalon', 'idSalon'); // Relación explícita de clave foránea
    }

    // Relación con Evento (Un salón tiene muchos eventos)
    public function eventos()
    {
        return $this->hasMany(Evento::class, 'idSalon', 'idSalon'); // Relación explícita de clave foránea
    }
}
