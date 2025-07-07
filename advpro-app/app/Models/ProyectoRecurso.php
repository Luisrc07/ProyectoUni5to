<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot; // Usar Pivot para tablas intermedias

class ProyectoRecurso extends Pivot
{
    use HasFactory;

    protected $table = 'proyecto_recursos'; // Nombre de la tabla pivote

    protected $fillable = [
        'proyecto_id',
        'asignable_id',
        'asignable_type',
        'cantidad', // Para equipos, será null para personal
        'fecha_asignacion',
        'fecha_fin_asignacion',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_fin_asignacion' => 'date',
    ];

    /**
     * Define la relación polimórfica inversa.
     * Permite acceder al modelo asignado (Staff o Equipo).
     */
    public function asignable()
    {
        return $this->morphTo();
    }

    /**
     * Relación con el proyecto al que se asigna el recurso.
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}

