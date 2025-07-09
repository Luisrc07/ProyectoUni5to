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
        'fecha_entrega', // Asegúrate de que esté aquí
        'costo',
        'estado',
        'serial'
    ];

    protected $casts = [
        'fecha_contrato' => 'date',
        'fecha_entrega' => 'date', // ¡AÑADE ESTA LÍNEA!
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Función para generar un serial único
    protected static function generateUniqueSerial()
    {
        $serial = Str::random(5);
        while (self::where('serial', $serial)->exists()) {
            $serial = Str::random(5);
        }
        return $serial;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contrato) {
            $contrato->serial = self::generateUniqueSerial();
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