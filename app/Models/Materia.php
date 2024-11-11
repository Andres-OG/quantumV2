<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'carrera_id'];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'idMateria');
    }

}
