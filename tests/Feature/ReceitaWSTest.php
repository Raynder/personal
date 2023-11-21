<?php

use Illuminate\Support\Facades\Http;

it('Validate return data from service', function () {
    $baseURL = "https://receitaws.com.br/v1/cnpj";
    $token = "c8a401fb6ecf04f9a3d331b1cf6a49ed08caec3e76ca1cdf0a12ebdb7c604dcb";
    $data['cnpj'] = '16548867000116';

    $this->response = Http::withToken($token)
        ->acceptJson()
        ->get("{$baseURL}/{$data['cnpj']}");

    $this->expect($this->response->status())->toEqual(200);
    $this->assertNotNull($this->response);
});
