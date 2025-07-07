<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'marca',
        'tipo_equipo',
        'estado',
        'ubicacion',
        'valor',
        'responsable', // Este es 'responsable' de la tabla equipos, que es un staff_id
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'valor' => 'float',
    ];

    /**
     * Obtiene el miembro del staff que es responsable del equipo.
     */
    public function responsableStaff()
    {
        return $this->belongsTo(Staff::class, 'responsable', 'id');
    }

    /**
     * Relación polimórfica inversa: Un equipo puede estar asignado a muchos proyectos.
     */
    public function proyectosAsignado()
    {
        return $this->morphToMany(Proyecto::class, 'asignable', 'proyecto_recursos', 'asignable_id', 'proyecto_id')
                    ->withPivot('id', 'cantidad', 'fecha_asignacion', 'fecha_fin_asignacion');
    }
}

