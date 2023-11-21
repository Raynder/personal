<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertBaseData extends Migration
{
    public function up()
    {
        $users = [
            '0' => [
                'name' => 'Flybi Sistemas',
                'email' => 'suporte@flybisistemas.com.br',
                'password' => bcrypt('suporte'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];
        DB::table('users')->insert($users);

        $permissions = [
            ['name' => 'dashboard',                   'description' => 'Menu Dashboard',      'order' => '1000', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],

            ['name' => 'cadastros.menu',                  'description' => 'Menu Cadastros',          'order' => '2000', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'cadastros.tributos',              'description' => 'Tributos',                'order' => '2020', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'cadastros.tributos.cadastrar',    'description' => 'Tributos - Cadastrar',    'order' => '2021', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'cadastros.tributos.alterar',      'description' => 'Tributos - Alterar',      'order' => '2022', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'cadastros.tributos.excluir',      'description' => 'Tributos - Excluir',      'order' => '2023', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            
            ['name' => 'cadastros.pagamentos',            'description' => 'Pagamentos',              'order' => '2030', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'cadastros.pagamentos.cadastrar',  'description' => 'Pagamentos - Cadastrar',  'order' => '2031', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'cadastros.pagamentos.alterar',    'description' => 'Pagamentos - Alterar',    'order' => '2032', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'cadastros.pagamentos.excluir',    'description' => 'Pagamentos - Excluir',    'order' => '2033', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            
            ['name' => 'operacional.menu',                'description' => 'Menu Operacional',        'order' => '3000', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'operacional.clientes',            'description' => 'Clientes',                'order' => '3010', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'operacional.clientes.cadastrar',  'description' => 'Clientes - Cadastrar',    'order' => '3011', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'operacional.clientes.alterar',    'description' => 'Clientes - Alterar',      'order' => '3012', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'operacional.clientes.excluir',    'description' => 'Clientes - Excluir',      'order' => '3013', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'operacional.clientes.ambiente',   'description' => 'Clientes - Ambiente',     'order' => '3014', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],

            ['name' => 'relatorios.menu',                 'description' => 'Menu Relatórios',         'order' => '4000', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],

            ['name' => 'utilitarios.menu',                'description' => 'Menu Utilitários',        'order' => '5000', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],

            ['name' => 'utilitarios.permissoes',          'description' => 'Permissões',              'order' => '5010', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.permissoes.cadastrar', 'description' => 'Permissões - Cadastrar',  'order' => '5011', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.permissoes.alterar',  'description' => 'Permissões - Alterar',    'order' => '5012', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.permissoes.excluir',  'description' => 'Permissões - Excluir',    'order' => '5013', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.grupos',              'description' => 'Grupos',                  'order' => '5020', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.grupos.cadastrar',    'description' => 'Grupos - Cadastrar',      'order' => '5021', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.grupos.alterar',      'description' => 'Grupos - Alterar',        'order' => '5022', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.grupos.excluir',      'description' => 'Grupos - Excluir',        'order' => '5023', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.grupos.permissoes',   'description' => 'Grupos - Permissões',     'order' => '5024', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.usuarios',            'description' => 'Usuários',                'order' => '5030', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.usuarios.cadastrar',  'description' => 'Usuários - Cadastrar',    'order' => '5031', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.usuarios.alterar',    'description' => 'Usuários - Alterar',      'order' => '5032', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.usuarios.excluir',    'description' => 'Usuários - Excluir',      'order' => '5033', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.configuracoes',        'description' => 'Configurações',          'order' => '5040', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.configuracoes.cadastrar', 'description' => 'Configurações - Cadastrar', 'order' => '5041', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.configuracoes.alterar', 'description' => 'Configurações - Alterar', 'order' => '5042', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'utilitarios.configuracoes.excluir', 'description' => 'Configurações - Excluir', 'order' => '5043', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'admin.utilitarios.configuracoes',            'description' => 'Configurações',                'order' => '5040', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'admin.utilitarios.configuracoes.cadastrar',  'description' => 'Configurações - Cadastrar',    'order' => '5041', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'admin.utilitarios.configuracoes.alterar',    'description' => 'Configurações - Alterar',      'order' => '5042', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
            ['name' => 'admin.utilitarios.configuracoes.excluir',    'description' => 'Configurações - Excluir',      'order' => '5043', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'guard_name' => 'web'],
        ];
        DB::table('permissions')->insert($permissions);

        $role = [
            '0' => ['name' => 'Master', 'guard_name' => 'web', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),]
        ];

        DB::table('roles')->insert($role);
        $role = Role::first();
        $permissions = Permission::all();
        $role->syncPermissions($permissions);

        $user = User::first();
        $user->assignRole('Master');

        // $empresa = [
        //     "cnpj" => "07640617000110",
        //     "cnpj_raiz" => "07640617",
        //     "razao_social" => "DISTRIBUIDORA BRASIL",
        //     "fantasia" => "BRASIL",
        //     "celular" => "62994891113",
        //     "telefone" => "62994891113",
        //     "regime_tributario" => null,
        //     "senha_certificado" => "07640617",
        // ];

        // DB::table('empresas')->insert($empresa);
    }

    public function down()
    {
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
    }
}
