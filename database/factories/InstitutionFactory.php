<?php

namespace Database\Factories;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InstitutionFactory extends Factory
{
    protected $model = Institution::class;

    public function definition()
    {
        return [
            'id_institution' => (string) Str::uuid(),
            'name' => $this->faker->company,
            'payment' => $this->faker->randomFloat(2, 0, 10000),
            'status' => $this->faker->boolean,
        ];
    }
}
