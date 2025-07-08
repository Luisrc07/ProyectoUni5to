<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_estimada_minutos',
        'presupuesto',
        'estado',
        'lugar',
        'responsable_id'
    ];

    protected $casts = [
        'duracion_estimada_minutos' => 'integer',
        'presupuesto' => 'float',
    ];

    public function responsable()
    {
        return $this->belongsTo(Staff::class, 'responsable_id');
    }

    public function personalAsignado()
    {
        return $this->morphedByMany(Staff::class, 'asignable', 'proyecto_recursos', 'proyecto_id', 'asignable_id')
                    ->wherePivot('asignable_type', Staff::class) // <-- ¡Añade esta línea!
                    ->withPivot('id', 'cantidad')
                    ->withTimestamps();
    }

    public function equiposAsignados()
    {
        return $this->morphedByMany(Equipo::class, 'asignable', 'proyecto_recursos', 'proyecto_id', 'asignable_id')
                    ->wherePivot('asignable_type', Equipo::class) // <-- ¡Añade esta línea!
                    ->withPivot('id', 'cantidad')
                    ->withTimestamps();
    }

    // Si no usas esta relación, puedes eliminarla por completo
    public function recursosProyectoRecurso()
    {
         return $this->hasMany(ProyectoRecurso::class);
    }
}