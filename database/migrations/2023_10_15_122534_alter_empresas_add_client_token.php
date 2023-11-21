<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterEmpresasAddClientToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('client_token', 255)->nullable();
        });

        // percorrer as empresas e setar o client_token como sendo hash de cnpj+data_atual
        $empresas = DB::table('empresas')->get();
        foreach ($empresas as $empresa) {
            $client_token = hash('sha256', $empresa->cnpj . Carbon::now()->format('YmdHis'));
            DB::table('empresas')->where('id', $empresa->id)->update(['client_token' => $client_token]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('empresas', ['client_token']);
    }
}
