<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAcessosNullableCertificadoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acessos', function(Blueprint $table) {
            $table->unsignedBigInteger('certificado_id')->nullable()->change();
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
            $table->unsignedBigInteger('certificado_id')->change();
        });
    }
}
