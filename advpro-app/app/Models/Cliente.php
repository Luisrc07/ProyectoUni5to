<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    // Agrega la propiedad $fillable
    protected $fillable = [
        'nombre',
        'tipo_documento',
        'documento',
        'email',
        'telefono',
        'direccion',
    ];
    
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

}