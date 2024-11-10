<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    use HasFactory;
    protected $table = 'salones';
    protected $primaryKey = 'idSalon';
    protected $fillable = ['nombre', 'idPiso'];

    public function piso()
    {
        return $this->belongsTo(Piso::class, 'idPiso');
    }
}
