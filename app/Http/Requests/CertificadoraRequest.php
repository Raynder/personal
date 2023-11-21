<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificadoraRequest extends FormRequest
{
    public function rules()
    {
        $rules = ['nome' => 'required|unique:certificadoras'];
        if ($this->method() === 'PATCH') {
            $rules = [
                'nome' => 'required|unique:certificadoras,nome,' . $this->certificadora
            ];
        }
        return $rules;
    }
}
