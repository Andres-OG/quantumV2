<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Jonathan',
            'firstNameMale' => 'Gonzalez',
            'firstNameFemale' => 'Gutierrez',
            'email' => 'admin@quantium.com',
            'password' => bcrypt('adminpassword'),
            'id_institution' => 1, // Asegúrate de este nombre
            'id_role' => 1,
            'status' => true,
        ]);
    }
}
