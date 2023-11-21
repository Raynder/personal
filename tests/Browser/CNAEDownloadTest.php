<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CNAEDownloadTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $dados = [];
        $this->browse(function (Browser $browser) use ($dados) {
            $browser->visit('https://www.contabilizei.com.br/contabilidade-online/cnae/');
            $tableRows = $browser->elements(".cnae-table > table tbody tr");
            $cnaeLista = [];
            # pegar as linhas impares
            for ($i = 1; $i < count($tableRows); $i++) {
                Log::info("Linha: {$i} | Qtde linhas: " . count($tableRows));
                $row = $tableRows[$i];
                $cols = $row->findElements(WebDriverBy::tagName("td"));
                #linhas impar possuem o registro completo
                $cnaeLista[] = [
                    'codigo' => $cols[0]->getText(),
                    'descricao' => $cols[1]->getText(),
                    'anexo' => $cols[2]->getText(),
                    'fator_r' => $cols[3]->getText()
                ];
            }
            $dados = $cnaeLista;

            $arquivo = fopen(storage_path('cnae.txt'), "w");
            $count = 0;
            $texto = "codigo;descricao;anexo;fator_r;contribuicao\r\n";
            fwrite($arquivo, $texto);
            foreach ($dados as $value) {
                $contr = '';
                if(str_contains($value['descricao'], 'comerc') || str_contains($value['descricao'], 'COMERC')){
                    $contr = 'SIM';
                }

                // $texto = "{$count} => ['codigo' => {$value['codigo']}, 'descricao' => '{$value['descricao']}', 'anexo' => '{$value['anexo']}', 'fator_r' => '{$value['fator_r']}', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],\r\n";
                $texto = "{$value['codigo']};'{$value['descricao']}';'{$value['anexo']}';'{$value['fator_r']}'\r\n";
                fwrite($arquivo, $texto);
                $count++;
            }
            fclose($arquivo);
        });
    }
}
