<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asiento extends Model
{
use HasFactory;

    protected $table = 'asiento_contable';
    protected $primaryKey = 'id_asiento';

    protected $fillable = [
        'fecha',
        'descripcion',
    ];

    // Un asiento tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetalleAsiento::class, 'id_asiento', 'id_asiento');
    }
}
?>