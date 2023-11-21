<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CorrectTablesGruposAndUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function(Blueprint $table){
            $table->string('apelido', 15)->nullable()->after('nome');
        });

        Schema::dropColumns('grupos', ['chave']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grupos', function(Blueprint $table){
            $table->string('chave', 15)->nullable()->after('nome');
        });

        Schema::dropColumns('usuarios', ['apelido']);
    }
}
