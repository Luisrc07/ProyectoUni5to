<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Importar la clase Str

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_cliente',
        'id_proyecto',
        'fecha_contrato',
        'costo',
        'estado',
        'fecha_inicio_proyecto',
        'fecha_fin_proyecto',    
        'serial' // Agregar el campo serial
    ];

    protected $casts = [
        'fecha_contrato' => 'date',
        'fecha_inicio_proyecto' => 'date', 
        'fecha_fin_proyecto' => 'date',    
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Función para generar un serial único
    protected static function generateUniqueSerial()
    {
        $serial = Str::random(5); // Genera un string aleatorio de 5 caracteres
        while (self::where('serial', $serial)->exists()) {
            $serial = Str::random(5); // Regenerar si ya existe
        }
        return $serial;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contrato) {
            $contrato->serial = self::generateUniqueSerial(); // Asignar el serial antes de crear
        });
    }
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_cliente');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}