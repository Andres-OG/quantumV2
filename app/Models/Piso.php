<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piso extends Model
{
    use HasFactory;

    protected $fillable = ['numero', 'idEdificio']; 

    public function edificio()
    {
        return $this->belongsTo(Edificio::class, 'idEdificio');
    }

    public function salones()
    {
        return $this->hasMany(Salon::class, 'idPiso');
    }
}
