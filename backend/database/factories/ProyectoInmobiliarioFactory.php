<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProyectoInmobiliarioFactory extends Factory
{
    protected $model = \App\Models\ProyectoInmobiliario::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->company(),
            'descripcion' => $this->faker->sentence(),
            'ubicacion' => $this->faker->address(),
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->date(),
            'estado' => $this->faker->randomElement(['En construcción', 'Terminado', 'En pausa', 'Cancelado']),
        ];
    }
}
