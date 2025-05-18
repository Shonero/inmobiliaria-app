<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectoInmobiliariosTable extends Migration
{
    public function up()
    {
        Schema::create('proyecto_inmobiliarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('ubicacion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', ['En construcción', 'Terminado', 'En pausa', 'Cancelado']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proyecto_inmobiliarios');
    }
}
