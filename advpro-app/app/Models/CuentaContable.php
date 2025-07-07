<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuentaContable extends Model
{
use HasFactory;

    protected $table = 'cuenta_contable'; // Especifica el nombre de la tabla
    protected $primaryKey = 'id_cuenta'; // Especifica la clave primaria

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'es_ajustable',
        'cuenta_padre_id',
    ];

    // Relación para la jerarquía (una cuenta padre puede tener muchas cuentas hijas)
    public function hijas()
    {
        return $this->hasMany(CuentaContable::class, 'cuenta_padre_id', 'id_cuenta');
    }

    // Relación inversa (una cuenta hija pertenece a una cuenta padre)
    public function padre()
    {
        return $this->belongsTo(CuentaContable::class, 'cuenta_padre_id', 'id_cuenta');
    }
    
    // Una cuenta puede estar en muchos detalles de asiento
    public function detallesAsiento()
    {
        return $this->hasMany(DetalleAsiento::class, 'id_cuenta', 'id_cuenta');
    }
}
?>