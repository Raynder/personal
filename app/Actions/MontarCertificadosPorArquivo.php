<?php

namespace App\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MontarCertificadosPorArquivo
{

    public function __invoke(Collection $acessos, $input)
    {
        $certificados = [];
        foreach($acessos as $acesso)
        {
            $acesso->status = "A";
            $acesso->usuario = $input["usuario"];
            $acesso->uuid_usuario = $input["uuid_usuario"];
            Log::info("Atualizando informações.");
            $acesso->save();

            if(!file_exists(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.pfx')) && !file_exists(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.p12')) && !file_exists(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.cer')))
            {
                Log::info("Certificado não encontrado no servidor.");
                return response()->json(['Certificado não encontrado no servidor.', 500]);
            }
            
            $ext = ['pfx', 'p12', 'cer'];
            foreach ($ext as $e) {
                if (file_exists(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.' . $e))) {
                    $content = file_get_contents(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.' . $e));
                    $acesso->content = base64_encode($content);
                    break;
                }
            }
            $acesso->fantasia = $acesso->certificado->fantasia;
            $acesso->limite = $acesso->data_limite->format('d/m/Y');
            $certificados[] = mb_convert_encoding($acesso->toArray(), 'UTF-8', 'UTF-8');
        }

        return $certificados;
    }
}