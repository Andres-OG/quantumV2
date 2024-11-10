<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $primaryKey = 'idGrupo';
    protected $fillable = ['nombre', 'idMateria', 'periodo'];

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'idMateria');
    }
}
