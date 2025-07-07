<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Equipo; // Importa el modelo Equipo
use App\Models\Staff;  // Importa el modelo Staff

class EquipoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que haya al menos algunos registros de Staff para poder asignarlos
        // Puedes crear staff aquí mismo si no tienes un StaffTableSeeder, o llamarlo.
        if (Staff::count() === 0) {
            Staff::factory()->count(10)->create(); // Crea 10 usuarios de Staff si no existen
        }

        // Crea 50 equipos usando el factory
        Equipo::factory()->count(50)->create();
    }
}