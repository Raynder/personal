<?php

namespace App\ViewModels;

use App\Models\Estado;
use App\Models\Municipio;
use Spatie\ViewModels\ViewModel;

class MunicipioViewModel extends ViewModel
{
    /** @var \App\Models\Municipio */
    public $municipio;

    public function __construct(Municipio $municipio = null)
    {
        $this->municipio = $municipio ?? new Municipio();
    }

    public function estados()
    {
        return Estado::all();
    }

    public function isEstadoSelected(Estado $estado): string
    {
        return $estado->id === $this->municipio->estado_id ? 'selected' : '';
    }
}
