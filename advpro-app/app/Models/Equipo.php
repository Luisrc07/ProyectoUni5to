<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    // Define el nombre de la tabla si no sigue la convención de nombres de Laravel (plural de la clase)
    protected $table = 'equipos';

    // Define las columnas que pueden ser asignadas masivamente
    protected $fillable = [
        'nombre',
        'descripcion', // Es nullable en la migración
        'marca',
        'tipo_equipo',
        'estado',
        'ubicacion',
        'valor',
        'cantidad',
        'responsable', // Es una foreign key, por lo tanto, es un ID

    ];

    // Define los tipos de datos para las columnas que necesitan ser casteadas
    protected $casts = [
        'valor' => 'decimal:2', // Castea a decimal con 2 decimales
    ];

    /**
     * Define la relación con el modelo Staff (el responsable del equipo).
     * Un equipo pertenece a un staff (responsable).
     * La columna 'responsable' en la tabla 'equipos' se relaciona con 'id' en la tabla 'staff'.
     * Es nullable, por lo que un equipo puede no tener un responsable asignado.
     */
    public function responsableStaff()
    {
        return $this->belongsTo(Staff::class, 'responsable', 'id');
    }

    public function proyectosAsignado()
    {
        return $this->morphToMany(Proyecto::class, 'asignable', 'proyecto_recursos', 'asignable_id', 'proyecto_id')
                     ->withPivot('id', 'cantidad', 'fecha_asignacion', 'fecha_fin_asignacion');
    }
}
