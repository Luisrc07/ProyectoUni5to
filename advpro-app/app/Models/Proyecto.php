<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'duracion_estimada_minutos', 'presupuesto',
        'estado', 'lugar', 'responsable_id'
    ];

    protected $casts = [
        'duracion_estimada_minutos' => 'integer', // ¡Añade esta línea!
        'presupuesto' => 'float', // También es buena práctica para decimales
        'fecha_inicio_estimada' => 'date', // Si vas a usar estos campos
        'fecha_fin_estimada' => 'date',   // Si vas a usar estos campos
    ];

    /**
     * Relación con el responsable del proyecto (Staff).
     */
    public function responsable()
    {
        return $this->belongsTo(Staff::class, 'responsable_id');
    }

    /**
     * Relación polimórfica genérica para todos los recursos asignados (personal y equipos).
     */
    public function recursos()
    {
        return $this->hasMany(ProyectoRecurso::class);
    }

    /**
     * Accesor para obtener el personal asignado a este proyecto a través de la relación polimórfica.
     */
    public function personalAsignado()
    {
        return $this->morphedByMany(Staff::class, 'asignable', 'proyecto_recursos', 'proyecto_id', 'asignable_id')
                    ->withPivot('id', 'fecha_asignacion', 'fecha_fin_asignacion', 'cantidad');
    }

    /**
     * Accesor para obtener los equipos asignados a este proyecto a través de la relación polimórfica.
     */
    public function equiposAsignados()
    {
        return $this->morphedByMany(Equipo::class, 'asignable', 'proyecto_recursos', 'proyecto_id', 'asignable_id')
                    ->withPivot('id', 'cantidad', 'fecha_asignacion', 'fecha_fin_asignacion');
    }
}