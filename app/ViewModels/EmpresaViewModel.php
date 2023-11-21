<?php

namespace App\ViewModels;

use App\Models\Empresa;
use Spatie\ViewModels\ViewModel;

class EmpresaViewModel extends ViewModel
{
    public $empresa;
    public $usuarios;

    public function __construct($usuarios, Empresa $empresa = null)
    {
        $this->usuarios = $usuarios;
        $this->empresa = $empresa ?? new Empresa();
    }
}
