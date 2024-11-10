<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $primaryKey = 'idHorario';
    protected $fillable = ['horaInicio', 'horaFin', 'dia'];

    public function maestro()
    {
        return $this->belongsTo(Maestro::class, 'idMaestro');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'idGrupo');
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'idSalon');
    }
}
