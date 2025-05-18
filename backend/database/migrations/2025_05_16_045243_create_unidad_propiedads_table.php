<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadPropiedadsTable extends Migration
{
    public function up()
    {
        Schema::create('unidad_propiedades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('numero_unidad');
            $table->string('tipo_unidad'); // Departamento, Casa, Oficina, etc.
            $table->float('metraje_cuadrado');
            $table->decimal('precio_venta', 15, 2);
            $table->enum('estado', ['Disponible', 'Vendido', 'Reservado']);
            $table->uuid('proyecto_inmobiliario_id');
            $table->uuid('cliente_id')->nullable();
            $table->timestamps();

            $table->foreign('proyecto_inmobiliario_id')->references('id')->on('proyecto_inmobiliarios')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('unidad_propiedades');
    }
}
