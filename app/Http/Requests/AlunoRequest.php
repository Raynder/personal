<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlunoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'senha' => 'required|string|max:255',
        ];
    }

}
