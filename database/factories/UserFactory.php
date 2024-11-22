<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'firstNameMale' => $this->faker->firstNameMale,
            'firstNameFemale' => $this->faker->firstNameFemale,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), 
            'id_institution' => (string) Str::uuid(), 
            'id_role' => 1,
            'status' => true,
        ];
    }
}
