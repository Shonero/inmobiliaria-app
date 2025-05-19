<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'rut' => $this->faker->unique()->regexify('[0-9]{7,8}-[0-9kK]'),
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'correo_electronico' => $this->faker->unique()->safeEmail(),
            'telefono_contacto' => $this->faker->numerify('9########'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
