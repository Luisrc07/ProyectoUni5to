<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'marca',
        'tipo_equipo',
        'estado',
        'ubicacion',
        'valor',
        'cantidad',
        'responsable_id', // <-- nombre estándar Laravel
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    public function responsableStaff()
    {
        return $this->belongsTo(Staff::class, 'responsable_id');
    }

    public function proyectosAsignado()
    {
        return $this->morphToMany(Proyecto::class, 'asignable', 'proyecto_recursos', 'asignable_id', 'proyecto_id')
                     ->withPivot('id', 'cantidad', 'fecha_asignacion', 'fecha_fin_asignacion')
                     ->wherePivot('asignable_type', self::class);
    }
}

