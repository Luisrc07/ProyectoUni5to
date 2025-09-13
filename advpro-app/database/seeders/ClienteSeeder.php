<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Ejecuta los seeds de la base de datos.
     */
    public function run(): void
    {
        Cliente::factory()->count(15)->create(); // Crea 15 registros de clientes
    }
}

