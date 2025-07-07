<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff'; // Asegúrate de que el nombre de la tabla sea correcto

    protected $fillable = [
        'nombre',
        'documento',
        'email',
        'telefono',
        'estado',
        'cargo',
        'tipo_documento',
        'direccion' // Asegúrate de que 'direccion' esté aquí si lo necesitas y es nullable en DB
    ];

    protected $casts = [
        // 'fecha_contratacion' y 'salario' han sido eliminados de la migración,
        // por lo tanto, también se eliminan de los casts aquí.
    ];

    /**
     * Relación polimórfica inversa para obtener los proyectos a los que este personal está asignado.
     * Si no usas esta relación, puedes eliminarla.
     */
    public function proyectos()
    {
        return $this->morphToMany(Proyecto::class, 'asignable', 'proyecto_recursos', 'asignable_id', 'proyecto_id')
                     ->withPivot('id', 'fecha_asignacion', 'fecha_fin_asignacion', 'cantidad');
    }
}
