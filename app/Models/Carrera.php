<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'created_by'];

    public function maestros()
    {
        return $this->hasMany(Maestro::class);
    }

    public function institucion()
    {
        return $this->belongsTo(Institution::class, 'created_by', 'id_institution');
    }
}
