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
        'valor',
        'estado',
        'tipo_equipo',
        'ubicacion',
        'responsable',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
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
     * Si no usas esta relación, puedes eliminarla.
     */
    public function proyectosAsignado()
    {
        return $this->morphToMany(Proyecto::class, 'asignable', 'proyecto_recursos', 'asignable_id', 'proyecto_id')
                     ->withPivot('id', 'cantidad', 'fecha_asignacion', 'fecha_fin_asignacion');
    }
}