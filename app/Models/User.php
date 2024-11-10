<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Cambiar esto
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable // Extiende solo de Authenticatable
{
    use HasFactory;

    protected $fillable = ['name', 'firstNameMale', 'firstNameFemale', 'email', 'password', 'id_institution', 'id_role', 'status'];
    protected $primaryKey = 'id_user'; 
}
