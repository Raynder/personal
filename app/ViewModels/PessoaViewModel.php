<?php

namespace App\ViewModels;

use App\Models\CnaeCategoria;
use App\Models\Cnae;
use App\Models\Estado;
use App\Models\Pessoa;
use App\Models\Tare;
use Spatie\ViewModels\ViewModel;

class PessoaViewModel extends ViewModel
{
    /** @var \App\Models\Pessoa */
    public $pessoa;

    public function __construct(Pessoa $pessoa = null)
    {
        $this->pessoa = $pessoa ?? new Pessoa();
    }

    public function estados()
    {
        return Estado::all();
    }

    public function isEstadoSelected(Estado $estado): string
    {
        if (!$this->pessoa->municipio) {
            return '';
        }
        return $estado->id === $this->pessoa->municipio->estado_id ? 'selected' : '';
    }

    public function classificacoes()
    {
        return CnaeCategoria::all();
    }

    public function isClassificacaoSelected(CnaeCategoria $classificacaoPessoa): string
    {
        return $classificacaoPessoa->id === $this->pessoa->classificacao_pessoa_id ? 'selected' : '';
    }

    public function tares()
    {
        return Tare::all();
    }

    public function isTareSelected(Tare $tare): string
    {
        $tarePessoa = $this->pessoa->tares->where('id', $tare->id)->first();
        return $tarePessoa ? 'checked' : '';
    }

    public function cnaes()
    {
        return Cnae::all();
    }

    public function isCnaeSelected(Cnae $cnae): string
    {
        $cnaePessoa = $this->pessoa->cnaes->where('id', $cnae->id)->first();
        return $cnaePessoa ? 'checked' : '';
    }
}
