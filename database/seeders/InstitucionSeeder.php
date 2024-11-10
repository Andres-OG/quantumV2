<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        Institution::factory()->create([
            'name'=> 'quantium',
            'payment' => 0
        ]);
        
    }
}
