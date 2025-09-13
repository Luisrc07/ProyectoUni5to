<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentTypes = ['V', 'J', 'E', 'G']; 

        return [
            'nombre' => $this->faker->name(),
            'tipo_documento' => $this->faker->randomElement($documentTypes),
            'documento' => $this->faker->unique()->numerify('#########'), 
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
        ];
    }
}

