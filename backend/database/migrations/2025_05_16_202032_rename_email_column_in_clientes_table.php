<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameEmailColumnInClientesTable extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->renameColumn('correo_electronico', 'email');
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->renameColumn('correo_electronico', 'correo');
        });
    }
}
