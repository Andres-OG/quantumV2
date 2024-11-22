<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edificio extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'id_institution'];

    public function pisos()
    {
        return $this->hasMany(Piso::class, 'idEdificio');
    }

    public function institucion()
    {
        return $this->belongsTo(Institution::class, 'id_institution', 'id_institution');
    }
}
