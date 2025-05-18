<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UnidadPropiedad extends Model
{
    use HasUuids;

    protected $table = 'unidad_propiedades';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'numero_unidad',
        'tipo_unidad',
        'metraje_cuadrado',
        'precio_venta',
        'estado',
        'proyecto_inmobiliario_id',
        'cliente_id',
    ];

    public function proyecto()
    {
        return $this->belongsTo(ProyectoInmobiliario::class, 'proyecto_inmobiliario_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    
}
