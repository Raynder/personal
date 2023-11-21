<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnCnpjCertificadoTable extends Migration
{
  
    public function up()
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->string('cnpj', 14)->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->dropUnique('certificados_cnpj_unique');
        });
    }
}
