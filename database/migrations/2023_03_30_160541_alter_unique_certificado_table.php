<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUniqueCertificadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->dropUnique('certificados_cnpj_unique');
            $table->unique(['empresa_id', 'cnpj']);
            $table->dropSoftDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->dropUnique('certificados_empresa_id_cnpj_unique');
            $table->unique('cnpj');
            $table->softDeletes();
        });
    }
}
