<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use App\Models\Grupo;
use App\Models\Usuario;
use App\Repositories\GrupoRepository;
use App\Repositories\UsuarioRepository;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    private $grupoRepository;
    private $usuarioRepository;

    public function __construct(GrupoRepository $grupoRepo, UsuarioRepository $usuarioRepo)
    {
        $this->usuarioRepository = $usuarioRepo;
        $this->grupoRepository = $grupoRepo;
    }

    public function index()
    {
        return view('grupos.index');
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $grupos = $this->grupoRepository->all($searchData);
        return view('grupos.table', [
            'grupos' => $grupos,
            "page" => $request->input("page", 0),
            'filter_take' => $request->input('filter_take'),
        ]);
    }

    public function create()
    {
        $usuarios = Usuario::orderBy('nome')->get();
        return view("grupos.create", [
            "grupo" => new Grupo(),
            "usuarios" => $usuarios,
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $grupo = $this->grupoRepository->create($input);

        if (!$grupo) {
            return response()->json("Erro ao salvar registro.", 500);
        }

        if (isset($input['certificados'])) {
            $certificados = $input['certificados'];
            $ids = array_column($certificados, 'certificado_id');
            $grupo->certificados()->sync($ids);
        }

        if (isset($input['usuario_id'])) {
            $usuarios = $input['usuario_id'];
            $grupo->usuarios()->sync($usuarios);
        }

        return response()->json("Registro salvo com sucesso.", 200);
    }

    public function find(Request $request)
    {
        $grupos = $this->grupoRepository->findToSelect2js($request->input("q"));
        return json_encode($grupos);
    }

    public function show($id)
    {
        $acessos = Acesso::where('usuario_id', $id)->get();
        $certificadosFlyToken = $acessos->whereNotNull('certificado_id')->groupBy('certificado_id');
        $outrosCertificados = $acessos->whereNull('certificado_id')->groupBy('chave');

        return view("usuarios.show", [
            'certificadosFlyToken' => $certificadosFlyToken,
            'outrosCertificados' => $outrosCertificados,
            'id' => $id
        ]);
    }
    public function edit($id)
    {
        $grupo = $this->grupoRepository->find($id);

        if (!$grupo) {
            return response()->json("Usuario não encontrado.", 500);
        }

        $usuarios = Usuario::orderBy('nome')->get();
        return view("grupos.edit", [
            "grupo" => $grupo,
            "usuarios" => $usuarios,
        ]);
    }

    public function update($id, Request $request)
    {
        $grupo = $this->grupoRepository->find($id);

        if (!$grupo) {
            return response()->json("Usuario não encontrado.", 500);
        }

        $input = $request->all();
        $this->grupoRepository->update($grupo, $input);

        if (isset($input['certificados'])) {
            $certificados = $input['certificados'];
            $ids = array_column($certificados, 'certificado_id');
            $grupo->certificados()->sync($ids);
        }

        if (isset($input['usuario_id'])) {
            $usuarios = $input['usuario_id'];
            $grupo->usuarios()->sync($usuarios);
        }

        return response()->json("Usuario atualizado com sucesso!", 200);
    }

    public function destroy($id)
    {
        $grupo = $this->grupoRepository->find($id);

        if (!$grupo) {
            return response()->json("Registro não encontrado.", 500);
        }
        $certificados = $grupo->certificados;
        $grupo->certificados()->detach($certificados);
        $usuarios = $grupo->usuarios;
        $grupo->usuarios()->detach($usuarios);
        $this->grupoRepository->delete($grupo);

        return response()->json("Registro excluído com sucesso!", 200);
    }
}
