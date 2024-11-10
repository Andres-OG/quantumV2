<?php

namespace Database\Factories;

use App\Models\User; // Asegúrate de que sea el nombre correcto
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'firstNameMale' => $this->faker->firstNameMale(),
            'firstNameFemale' => $this->faker->firstNameMale(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), 
            'id_institution' => 1,
            'id_role' => 1,
            'status' => true,
        ];
    }
}
