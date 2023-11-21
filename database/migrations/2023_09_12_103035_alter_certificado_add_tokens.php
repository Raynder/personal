<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCertificadoAddTokens extends Migration
{
    public function up()
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->string('token', 50)->nullable();
            $table->date('validade_token')->nullable();
        });
    }

    public function down()
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->dropColumn('token', 50);
            $table->dropColumn('validade_token');
        });
    }
}
