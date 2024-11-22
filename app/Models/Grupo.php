<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $primaryKey = 'idGrupo';
    protected $fillable = ['nombre', 'idMateria'];

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'idMateria');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'idGrupo');
    }
    // Modelo Grupo (App\Models\Grupo)
    public function carrera()
    {
        return $this->belongsTo(Carrera::class); // Relación belongsTo, si un grupo pertenece a una carrera
    }
}
