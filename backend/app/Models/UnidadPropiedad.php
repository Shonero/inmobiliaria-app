<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnidadPropiedad extends Model
{
    use HasUuids, HasFactory;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
    
}
