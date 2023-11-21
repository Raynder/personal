<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CfopDownloadTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCfopDownload()
    {
        $dados = [];
        $this->browse(function (Browser $browser) use ($dados) {
            $browser->visit('https://www.valor.srv.br/fiscal/cfop.php');
            $tableRows = $browser->elements("#cfopTable tbody tr");
            $cfopLista = [];
            $linha = 0;
            # pegar as linhas impares
            for ($i = 0; $i < count($tableRows); $i++) {
                $row = $tableRows[$i];
                $cols = $row->findElements(WebDriverBy::tagName("td"));
                if ($i % 2 == 0) {
                    $linha++;
                    #linhas impar possuem o registro completo
                    $cfopLista[$linha] = [
                        'cde' => $cols[0]->getText(),
                        'cfe' => $cols[1] ? $cols[1]->getText() : 'N',
                        'cfp' => $cols[2] ? $cols[2]->getText() : 'N',
                        'descricao' => $cols[3] ? $cols[3]->getText() : 'N',
                    ];
                }
            }
            //$dados = $cfopLista;
            $linha = 1;
            #linhas impar possuem o registro completo
            for ($i = 1; $i < count($tableRows); $i++) {
                $row = $tableRows[$i];
                $cols = $row->findElements(WebDriverBy::tagName("td"));
                if ($i % 2 != 0) {
                    $linha++;
                    $cfop = $cfopLista[$linha - 1];
                    $cfop['aplicacao'] = $cols[0] ? $cols[0]->getText() : 'N';
                    $cfopLista[$linha - 1] = $cfop;
                }
            }
            $dados = $cfopLista;

            $arquivo = fopen(storage_path('cfop.txt'), "w");
            $count = 0;
            foreach ($dados as $key => $value) {
                if (isset($value['cde'])) {
                    $codigo = $value['cde'] ? str_replace(".", "", $value['cde']) : 0;
                    $texto = "{$count} => ['codigo' => {$codigo}, 'descricao' => '{$value['descricao']}', 'aplicacao' => '{$value['aplicacao']}', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],\r\n";
                    fwrite($arquivo, $texto);
                    $count++;
                }
                if (isset($value['cfe'])) {
                    $codigo = $value['cfe'] ? str_replace(".", "", $value['cfe']) : 0;
                    $texto = "{$count} => ['codigo' => {$codigo}, 'descricao' => '{$value['descricao']}', 'aplicacao' => '{$value['aplicacao']}', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],\r\n";
                    fwrite($arquivo, $texto);
                    $count++;
                }
                if (isset($value['cfp'])) {
                    $codigo = $value['cfp'] ? str_replace(".", "", $value['cfp']) : 0;
                    $texto = "{$count} => ['codigo' => {$codigo}, 'descricao' => '{$value['descricao']}', 'aplicacao' => '{$value['aplicacao']}', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],\r\n";
                    fwrite($arquivo, $texto);
                    $count++;
                }
            }
            fclose($arquivo);
        });
    }
}
