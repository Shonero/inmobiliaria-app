<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'clientes';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'rut',
        'nombre',
        'apellido',
        'correo_electronico',
        'telefono_contacto',
    ];

    public function unidades()
    {
        return $this->hasMany(UnidadPropiedad::class, 'cliente_id');
    }
}
