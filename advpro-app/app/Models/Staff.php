<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff'; // Asegúrate de que el nombre de la tabla sea correcto

    protected $fillable = [
        'nombre', 'documento', 'cargo', 'departamento', 'fecha_contratacion',
        'salario', 'estado', 'email', 'telefono', 'tipo_documento', 'direccion'
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
        'salario' => 'float',
    ];

    /**
     * Relación polimórfica inversa para obtener los proyectos a los que este personal está asignado.
     */
    public function proyectos()
    {
        return $this->morphToMany(Proyecto::class, 'asignable', 'proyecto_recursos', 'asignable_id', 'proyecto_id')
                    ->withPivot('id', 'fecha_asignacion', 'fecha_fin_asignacion', 'cantidad');
    }
}

