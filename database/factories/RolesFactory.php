<?php

namespace Database\Factories;

use App\Models\Roles; // Asegúrate de importar el modelo correcto
use Illuminate\Database\Eloquent\Factories\Factory;

class RolesFactory extends Factory
{
    protected $model = Roles::class; 

    public function definition()
    {
        return [
            'rol' => $this->faker->word(), 
        ];
    }
}
