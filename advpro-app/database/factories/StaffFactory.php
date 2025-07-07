<?php

namespace Database\Factories;

use App\Models\Staff; // Asegúrate de importar tu modelo Staff
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'documento' => $this->faker->unique()->numerify('##########'), 
            'tipo_documento' => $this->faker->randomElement(['V', 'J', 'E', 'G']), 
            'nombre' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
            'cargo' => $this->faker->randomElement(['Gerente', 'Técnico', 'Administrativo', 'Asistente', 'Limpieza', 'Seguridad']),
            'estado' => $this->faker->randomElement(['Activo', 'Inactivo', 'Suspendido']), 
        ];
    }
}