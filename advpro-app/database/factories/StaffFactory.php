<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Staff;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentTypes = ['V', 'J', 'E', 'P'];
        $cargos = ['Produccion', 'Direccion', 'Guion y Desarrollo', 'Sonido',
        'Iluminacion y Electricos','Fotografia y Camara'];
        $estados = ['Activo', 'Inactivo'];

        return [
            'documento' => $this->faker->unique()->numerify('#########'), 
            'tipo_documento' => $this->faker->randomElement($documentTypes),
            'nombre' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
            'cargo' => $this->faker->randomElement($cargos),
            'estado' => $this->faker->randomElement($estados),
        ];
    }
}

