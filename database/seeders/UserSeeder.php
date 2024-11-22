<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Institution; // Asegúrate de importar el modelo Institution
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca el ID de la institución "Admin Institution"
        $adminInstitutionId = Institution::where('name', 'Admin Institution')->value('id_institution');

        // Asegúrate de que la institución existe
        if (!$adminInstitutionId) {
            throw new \Exception('La institución "Admin Institution" no se encontró. Asegúrate de que se haya creado correctamente.');
        }

        // Crea el usuario SuperAdmin
        User::create([
            'name' => 'Jonathan',
            'firstNameMale' => 'Gonzalez',
            'firstNameFemale' => 'Gutierrez',
            'email' => 'admin@quantium.com',
            'password' => Hash::make('adminpassword'),
            'id_institution' => $adminInstitutionId,
            'id_role' => 1, // Rol de SuperAdmin
            'status' => true,
        ]);
    }
}
