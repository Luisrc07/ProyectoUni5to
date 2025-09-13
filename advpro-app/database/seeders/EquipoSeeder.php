<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Equipo;

class EquipoSeeder extends Seeder
{
    /**
     * Ejecuta los seeds de la base de datos.
     */
    public function run(): void
    {
        Equipo::factory()->count(20)->create(); 
    }
}

