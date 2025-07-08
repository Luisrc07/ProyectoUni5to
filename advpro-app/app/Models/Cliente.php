<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Asegúrate de que esto esté si usas factories

class Cliente extends Model
{
    use HasFactory; // Si usas factories, mantén esto

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'tipo_documento',
        'documento',
        'email',
        'telefono',
        'direccion',
    ];
    
    /**
     * Relación: Un cliente puede tener muchos contratos.
     * La clave foránea en la tabla 'contratos' es 'id_cliente'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'id_cliente'); 
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_cliente'); // Asumiendo 'id_cliente' en la tabla proyectos
    }
}