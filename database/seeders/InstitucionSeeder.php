<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Institution;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear solo la institución predeterminada para el Super Admin
        Institution::firstOrCreate(
            ['name' => 'Admin Institution'], // Condición para evitar duplicados
            [
                'id_institution' => (string) Str::uuid(), // UUID para la clave primaria
                'payment' => 0,
                'status' => true,
            ]
        );
    }
}
