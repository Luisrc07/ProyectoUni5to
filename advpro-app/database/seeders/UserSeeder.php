<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Ejecuta los seeds de la base de datos.
     */
    public function run(): void
    {
        // Crea el usuario específico solicitado
        User::create([
            'name' => 'Prueba',
            'email' => 'prueba@gmail.com',
            'password' => Hash::make('123456789'), // Hashea la contraseña
            'email_verified_at' => now(), // Establece el correo como verificado
        ]);
        // User::factory()->count(10)->create();
    }
}

