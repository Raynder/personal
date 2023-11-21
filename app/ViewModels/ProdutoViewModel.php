<?php

namespace App\ViewModels;

use App\Models\ClassificacaoProduto;
use App\Models\Cnae;
use App\Models\Ncm;
use App\Models\Produto;
use Spatie\ViewModels\ViewModel;

class ProdutoViewModel extends ViewModel
{
    /** @var \App\Models\Produto */
    public $produto;

    public function __construct(Produto $produto = null)
    {
        $this->produto = $produto ?? new Produto();
    }

    public function ncms()
    {
        return Ncm::all();
    }

    public function isNcmSelected(Ncm $ncm): string
    {
        if (!$this->produto->ncm) {
            return '';
        }
        return $ncm->id === $this->produto->ncm_id ? 'selected' : '';
    }

    public function classificacoes()
    {
        return ClassificacaoProduto::all();
    }

    public function isClassificacaoSelected(ClassificacaoProduto $classificacaoProduto): string
    {
        return $classificacaoProduto->id === $this->produto->classificacao_produto_id ? 'selected' : '';
    }

    public function cnaes()
    {
        return Cnae::all();
    }

    public function isCnaeSelected(Cnae $cnae): string
    {
        $cnaeProduto = $this->produto->cnaes->where('id', $cnae->id)->first();
        return $cnaeProduto ? 'checked' : '';
    }
}
