<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepository = $permissionRepo;
    }

    public function index()
    {
        return view("permissions.index");
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $permissions = $this->permissionRepository->all($searchData);

        return view("permissions.table", [
            "permissions" => $permissions,
            "page" => $request->input("page", 0)
        ]);
    }

    public function find(Request $request)
    {
        $permissions = $this->permissionRepository->findToSelect2js($request->input("q"));
        return json_encode($permissions);
    }

    public function create()
    {
        return view("permissions.create", ["permission" => new Permission()]);
    }

    public function store(PermissionRequest $request)
    {
        $input = $request->all();
        $this->permissionRepository->create($input);
        return response()->json("Salvo com sucesso.", 200);
    }

    public function show($id)
    {
        $permission = $this->permissionRepository->find($id);
        $permission = '';

        if (!$permission) {
            return response()->json("Registro não encontrado.", 500);
        }

        return view("permissions.show", ["permission" => $permission]);
    }

    public function edit($id)
    {
        $permission = $this->permissionRepository->find($id);

        if (!$permission) {
            return response()->json("Registro não encontrado.", 500);
        }

        return view("permissions.edit", ["permission" => $permission]);
    }

    public function update($id, PermissionRequest $request)
    {
        $permission = $this->permissionRepository->find($id);

        if (!$permission) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->permissionRepository->update($permission, $request->all());
        return response()->json("Salvo com sucesso.", 200);
    }

    public function destroy($id)
    {
        $permission = $this->permissionRepository->find($id);

        if (!$permission) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->permissionRepository->delete($permission);
        return $id;
    }
}
