<?php

namespace App\ViewModels;

use App\Models\Empresa;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\ViewModels\ViewModel;

class UserViewModel extends ViewModel
{
    public $user;

    public function __construct(User $user = null)
    {
        $this->user = $user ?? new User();
    }

    public function empresas()
    {
        return Empresa::all();
    }

    public function isEmpresaSelected(Empresa $empresa): string
    {
        $empresaUser = $this->user->empresas->where('id', $empresa->id)->first();
        return $empresaUser ? 'checked' : '';
    }

    public function roles()
    {
        return Role::all();
    }

    public function isRoleSelected(Role $role): string
    {
        if (count($this->user->roles) == 0) {
            return '';
        }
        return $role->id === $this->user->roles()->first()->id ? 'selected' : '';
    }
}
