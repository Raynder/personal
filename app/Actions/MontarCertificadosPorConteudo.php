<?php

namespace App\Actions;

use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

class MontarCertificadosPorConteudo
{
    private $certificados = [];
    private $usuario = null;
    private $empresa_id = null;

    public function __invoke($acessos, $input)
    {
        // se existir acessos->id
        if(isset($acessos->id)){
            $this->empresa_id = $acessos->certificado->empresa_id;
            $this->createUsuario($input);
            $this->createAcessoCertificado($acessos, $input);
        }
        else{
            $this->empresa_id = $acessos[0]->certificado->empresa_id;
            $this->createUsuario($input);
            foreach($acessos as $acesso)
            {
                $this->createAcessoCertificado($acesso, $input);
            }
        }

        return $this->certificados;
    }

    private function createAcessoCertificado($acesso, $input)
    {
        $acesso->status = "A";
        $acesso->usuario = $this->usuario->nome;
        $acesso->uuid_usuario = $this->usuario->uuid;
        $acesso->empresa_id = $this->empresa_id;
        Log::info("Atualizando informações.");
        $acesso->save();

        if($acesso->certificado->criptografia <= 1){
            $acesso = $this->cbc($acesso);
        }
        else{
            $acesso = $this->ctr($acesso);
        }

        $acesso->fantasia = $acesso->certificado->fantasia;
        $acesso->limite = $acesso->data_limite->format('d/m/Y');
        $this->certificados[] = mb_convert_encoding($acesso->toArray(), 'UTF-8', 'UTF-8');
    }

    public function cbc($acesso){
        if($acesso->certificado->criptografia == 0){
            $ext = ['pfx', 'p12', 'cer'];
            foreach ($ext as $e) {
                if (file_exists(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.' . $e))) {
                    $content2 = file_get_contents(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.' . $e));
                    $acesso->content2 = base64_encode($content2);
                    break;
                }
            }
        }

        $base64 = openssl_decrypt($acesso->certificado->certificado, 'aes-256-cbc', 'flybi2022', 0, 'flybisistemas123');
        $acesso->content = $base64;
        return $acesso;
    }

    public function ctr($acesso){
        $base64 = openssl_decrypt($acesso->certificado->certificado, 'aes-256-ctr', 'flybi2022', 0, 'flybisistemas123');
        $acesso->content = $base64;
        return $acesso;
    }

    private function createUsuario($input)
    {
        $usuario = Usuario::firstOrCreate(
            [
            'uuid' => $input['uuid_usuario'],
            ], [
                'nome' => $input['usuario'],
                'apelido' => $input['usuario'],
                'uuid' => $input['uuid_usuario'],
                'empresa_id' => $this->empresa_id,
            ]
        );

        $this->usuario = $usuario;
    }
}