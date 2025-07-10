<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeds de la aplicación.
     */
    public function run(): void
    {
       $this->call([
            UserSeeder::class,
            StaffSeeder::class, 
            ClienteSeeder::class,
            EquipoSeeder::class, 
]);
    }
}
