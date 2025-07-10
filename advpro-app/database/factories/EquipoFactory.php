<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Equipo;
use App\Models\Staff; // Importa el modelo Staff para vincular responsable_id

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipo>
 */
class EquipoFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente.
     *
     * @var string
     */
    protected $model = Equipo::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $marcas = ['Dell', 'HP', 'Lenovo', 'Apple', 'Samsung', 'LG', 'Sony'];
        $tiposEquipo = ['Portátil', 'Escritorio', 'Monitor', 'Impresora', 'Servidor', 'Smartphone'];
        $estados = ['Usado', 'Nuevo', 'Reparado'];
        $ubicaciones = ['Estudio A', 'Bodega', 'Estudio B', 'Estudio C'];

        return [
            'nombre' => $this->faker->word() . ' ' . $this->faker->randomElement($tiposEquipo),
            'descripcion' => $this->faker->sentence(),
            'marca' => $this->faker->randomElement($marcas),
            'tipo_equipo' => $this->faker->randomElement($tiposEquipo),
            'estado' => $this->faker->randomElement($estados),
            'ubicacion' => $this->faker->randomElement($ubicaciones),
            'cantidad' => $this->faker->numberBetween(1, 10),
            'valor' => $this->faker->randomFloat(2, 50, 1000), // Valor entre 100.00 y 5000.00
            'responsable_id' => Staff::inRandomOrder()->first()->id ?? null, // Vincula a un miembro del personal existente
        ];
    }
}

