<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleAsiento extends Model
{
    use HasFactory;

    protected $table = 'detalle_asiento';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_asiento',
        'id_cuenta',
        'debe',
        'haber',
        'descripcion_linea',
    ];

    // Un detalle pertenece a un asiento
    public function asientoContable()
    {
        return $this->belongsTo(Asiento::class, 'id_asiento', 'id_asiento');
    }

    // Un detalle pertenece a una cuenta contable
    public function cuentaContable()
    {
        return $this->belongsTo(CuentaContable::class, 'id_cuenta', 'id_cuenta');
    }
}
?>