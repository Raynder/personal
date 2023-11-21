<?php

namespace Database\Seeders;

use App\Models\Certificado;
use Illuminate\Database\Seeder;

class CorrigirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $certificados = Certificado::all();
        foreach($certificados as $certificado){

            $ext = ['pfx', 'p12', 'cer'];
            foreach ($ext as $e) {
                if (file_exists(storage_path('app/certificados/' . $certificado->cnpj . '.' . $e))) {
                    $content = file_get_contents(storage_path('app/certificados/' . $certificado->cnpj . '.' . $e));
                    $content = base64_encode($content);
                    $certificado->certificado = openssl_encrypt($content, 'aes-256-cbc', 'flybi2022', 0, 'flybisistemas123');
                    break;
                }
            }
            $certificado->save();
        }
    }
}
