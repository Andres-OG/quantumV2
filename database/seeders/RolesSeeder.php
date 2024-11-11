<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roles:: factory()->create([
            'rol'=>'Super Admin'
        ]);
        Roles:: factory()->create([
            'rol'=>'Admin Institucion'
        ]);
        Roles:: factory()->create([
            'rol'=>'usuario'
        ]);
    }
}
