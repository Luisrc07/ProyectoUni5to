<?php

namespace Database\Factories;

use App\Models\Equipo; // Asegúrate de importar tu modelo Equipo
use App\Models\Staff;  // Asegúrate de importar tu modelo Staff, para la clave foránea
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipo>
 */
class EquipoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Equipo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       
        $staffIds = Staff::pluck('id')->all();

        return [
            'nombre' => $this->faker->words(2, true), 
            'descripcion' => $this->faker->sentence(), 
            'marca' => $this->faker->company(), 
            'tipo_equipo' => $this->faker->randomElement(['Computadora', 'Impresora', 'Monitor', 'Teclado', 'Mouse', 'Router', 'Servidor']), 
            'estado' => $this->faker->randomElement(['Nuevo', 'Usado', 'Reparado']), 
            'ubicacion' => $this->faker->address(),
            'responsable' => $this->faker->randomElement($staffIds) ?? null, // Asigna un ID de staff existente, o null si no hay staff
        ];
    }
}