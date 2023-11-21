<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
    }

    public function index()
    {
        return view("roles.index");
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $roles = $this->roleRepository->all($searchData);

        return view("roles.table", [
            "roles" => $roles,
            "page" => $request->input("page", 0)
        ]);
    }

    public function find(Request $request)
    {
        $roles = $this->roleRepository->findToSelect2js($request->input("q"));
        return json_encode($roles);
    }

    public function create()
    {
        return view("roles.create", ["role" => new Role()]);
    }

    public function store(RoleRequest $request)
    {
        $input = $request->all();
        $this->roleRepository->create($input);
        return response()->json("Salvo com sucesso.", 200);
    }

    public function show($id)
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            return response()->json("Registro não encontrado.", 500);
        }

        return view("roles.show", ["role" => $role]);
    }

    public function edit($id)
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            return response()->json("Registro não encontrado.", 500);
        }

        return view("roles.edit", ["role" => $role]);
    }

    public function update($id, RoleRequest $request)
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->roleRepository->update($role, $request->all());
        return response()->json("Salvo com sucesso.", 200);
    }

    public function destroy($id)
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->roleRepository->delete($role);
        return $id;
    }

    public function permissionForm($id)
    {
        $role = $this->roleRepository->find($id);
        if (!$role) {
            return response()->json("Não encontrado.", 500);
        }
        $permissions = Permission::orderBy('order', 'asc')->get();
        return view(".roles.permissionForm", [
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    public function updatePermissions($id, Request $request)
    {
        $roles = $this->roleRepository->find($id);
        if (!$roles) {
            return response()->json('Não encontrado!', 409);
        }
        $input = $request->all();
        if (isset($input['permission_id'])) {
            $ids = $input['permission_id'];
            $roles->syncPermissions($ids);
        }
        return response()->json('Salvo com sucesso.', 200);
    }
}
