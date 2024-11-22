<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable
{
    use HasFactory, HasApiTokens; 
    protected $fillable = [
        'name', 
        'firstNameMale', 
        'firstNameFemale', 
        'email', 
        'password', 
        'id_institution', 
        'id_role', 
        'status',
        'account_number',
    ];


    protected $primaryKey = 'id_user';

    public $incrementing = false;

    protected $keyType = 'uuid';

    /**
     * Relación con el modelo Institution
     * Cada usuario pertenece a una institución
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution', 'id_institution');
    }
}
