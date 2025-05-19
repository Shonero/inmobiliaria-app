<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;  // <-- Agregar esta línea

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProyectoInmobiliario extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'proyecto_inmobiliarios';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nombre',
        'descripcion',
        'ubicacion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function unidades()
    {
        return $this->hasMany(UnidadPropiedad::class, 'proyecto_inmobiliario_id');
    }
}
