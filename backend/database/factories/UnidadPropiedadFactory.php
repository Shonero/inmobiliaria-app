<?php

namespace Database\Factories;

use App\Models\UnidadPropiedad;
use App\Models\ProyectoInmobiliario;
use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;

class UnidadPropiedadFactory extends Factory
{
    protected $model = UnidadPropiedad::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'numero_unidad' => $this->faker->unique()->numerify('U###'),
            'tipo_unidad' => $this->faker->randomElement(['Departamento', 'Casa', 'Oficina']),
            'metraje_cuadrado' => $this->faker->numberBetween(40, 120),
            'precio_venta' => $this->faker->numberBetween(50000000, 150000000),
            'estado' => $this->faker->randomElement(['Disponible', 'Vendido', 'Reservado']),
            'proyecto_inmobiliario_id' => ProyectoInmobiliario::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
