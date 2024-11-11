<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maestro extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'no_cuenta', 'carrera_id'];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
}
