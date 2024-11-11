<?php

namespace Database\Factories;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

class institutionFactory extends Factory
{
    protected $model = Institution::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'payment' => 0, 
        ];
    }
}
