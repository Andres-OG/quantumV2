<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'payment', 'status', 'ColorP', 'ColorS', 'terms_accepted'];

    // Clave primaria personalizada
    protected $primaryKey = 'id_institution';

    // Deshabilitar auto-incremento para la clave primaria
    public $incrementing = false;

    // Tipo de la clave primaria
    protected $keyType = 'string';

    // Relación con usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'id_institution');
    }

    // Relación con el administrador
    public function admin()
    {
        return $this->hasOne(User::class, 'id_institution', 'id_institution')->where('id_role', 2);
    }

    // Relación con carreras que creó la institución
    public function carreras()
    {
        return $this->hasMany(Carrera::class, 'created_by', 'id_institution');
    }

    // Generar automáticamente un ID personalizado al crear una nueva institución
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($institution) {
            $institution->id_institution = (string) Str::uuid();
        });
    }

    // Mutator para formatear los colores
    public function setColorPAttribute($value)
    {
        $this->attributes['ColorP'] = $this->formatColor($value);
    }

    public function setColorSAttribute($value)
    {
        $this->attributes['ColorS'] = $this->formatColor($value);
    }

    // Método privado para asegurar el formato de colores
    private function formatColor($color)
    {
        return preg_match('/^#[0-9A-Fa-f]{6}$/', $color) ? $color : '#ffffff';
    }
}
