<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $fillable = ['rol'];
  
    protected $primaryKey = 'id_role'; 
   
    public $incrementing = true; 

    public function institucion()
    {
        return $this->belongsTo(Institution::class);
    }
}
