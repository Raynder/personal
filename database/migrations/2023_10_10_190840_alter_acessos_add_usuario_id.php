<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAcessosAddUsuarioId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acessos', function(Blueprint $table) {
            $table->unsignedBigInteger('usuario_id')->nullable();

            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acessos', function(Blueprint $table) {
            $table->dropForeign('acessos_usuario_id_foreign');
            $table->dropColumn('usuario_id');
        });
    }
}
