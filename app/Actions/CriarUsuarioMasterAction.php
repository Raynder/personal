<?php

namespace App\Actions;

use App\Models\Empresa;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class CriarUsuarioMasterAction
{
    /**
     * Criar o usuário master da empresa
     *
     * @param Empresa $empresa
     * @return Pessoa
     */
    public function __invoke(Empresa $empresa, $senha = null): void
    {
        $dadosUsuario = [
            'name' => $empresa->razao_social,
            'email' => $empresa->email,
            'empresa_id' => $empresa->id,
            'password' => $senha ? bcrypt($senha) : null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $user = User::create($dadosUsuario);
        $user->empresas()->attach($empresa);

        $role = Role::where('name', 'MASTER')->first();
        $user->roles()->attach($role);
    }
}
