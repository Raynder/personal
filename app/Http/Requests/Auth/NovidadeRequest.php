<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class NovidadeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'type' => 'required|integer',
            'version' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
        ];
    }

}
