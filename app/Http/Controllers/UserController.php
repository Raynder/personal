<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\ViewModels\UserViewModel;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private $userRepository;
    private $roleRepository;

    public function __construct(
        UserRepository $userRepo,
        RoleRepository $roleRepo
    ) {
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
    }

    public function index()
    {
        return view("users.index");
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $users = $this->userRepository->all($searchData);

        return view("users.table", [
            "users" => $users,
            "page" => $request->input("page", 0)
        ]);
    }

    public function find(Request $request)
    {
        $users = $this->userRepository->findToSelect2js($request->input("q"));
        return json_encode($users);
    }

    public function create()
    {
        return view("users.create", new UserViewModel());
    }

    public function store(UserRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = $this->userRepository->create($input);
        $role = $this->roleRepository->find($input['role_id']);
        $user->assignRole($role->name);
        $empresas = $request->input('empresas');
        return response()->json("Salvo com sucesso.", 200);
    }

    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return response()->json("Registro não encontrado.", 500);
        }

        return view("users.show", ["user" => $user]);
    }

    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return response()->json("Registro não encontrado.", 500);
        }
        return view("users.edit", new UserViewModel($user));
    }

    public function update($id, UserRequest $request)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->userRepository->update($user, $request->all());
        $role = $this->roleRepository->find($request->input('role_id'));
        $user->syncRoles([$role->name]);
        $empresas = $request->input('empresas');
        $user->empresas()->sync($empresas);
        return response()->json("Salvo com sucesso.", 200);
    }

    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->userRepository->delete($user);
        return $id;
    }

    public function permissionForm($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json("Não encontrado.", 500);
        }
        $permissions = Permission::orderBy('order', 'asc')->get();
        return view(".users.permissionForm", [
            'user' => $user,
            'permissions' => $permissions
        ]);
    }

    public function updatePermissions($id, Request $request)
    {
        $users = $this->userRepository->find($id);
        if (!$users) {
            return response()->json('Não encontrado!', 409);
        }
        $input = $request->all();
        if (isset($input['permission_id'])) {
            $ids = $input['permission_id'];
            $users->syncPermissions($ids);
        }
        return response()->json('Salvo com sucesso.', 200);
    }
}
