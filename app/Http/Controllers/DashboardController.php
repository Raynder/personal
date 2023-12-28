<?php

namespace App\Http\Controllers;

use App\Helpers\FormatterHelper;
use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Treino;
use App\Models\Exercicio;
use App\Repositories\AlunoRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $alunoRepository;

    public function __construct(AlunoRepository $alunoRepo)
    {
        $this->alunoRepository = $alunoRepo;
    }

    public function index()
    {
        $params = [
        ];
        $alunos = $this->alunoRepository->validate($params);
        $usuarios = Exercicio::where('empresa_id', session()->get('empresa_id'))->get();
        $treinos = Treino::where('empresa_id', session()->get('empresa_id'))->get();

        $alunos = $alunos->map(function ($item, $key) {
            $item->data_validade = Carbon::parse($item->data_validade);
            $item->dias_validade = $item->data_validade->diffInDays(Carbon::now());
            return $item;
        });
        
        $alunos7 = $alunos->whereBetween('dias_validade', [1, 7]);
        $alunos15 = $alunos->whereBetween('dias_validade', [8, 15]);
        $alunos30 = $alunos->whereBetween('dias_validade', [16, 31]);
        
        return view('dashboard.index', [
            'alunos' => $alunos,
            'usuarios' => $usuarios,
            'treinos' => $treinos,
            'alunos7' => $alunos7,
            'alunos15' => $alunos15,
            'alunos30' => $alunos30,
        ]);
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        if (isset($searchData['filter_cnpj'])) {
            $searchData['filter_cnpj'] = FormatterHelper::onlyNumbers($searchData['filter_cnpj']);
        }
        $alunos = $this->alunoRepository->validate($searchData);
        $alunos->map(function ($item, $key) {
            $item->data_validade = Carbon::parse($item->data_validade);
            $item->dias_validade = $item->data_validade->diffInDays(Carbon::now());
            return $item;
        });
    
        $alunos->whereBetween('dias_validade', [0, 7])->map(function ($item, $key) {
            $item->class = 'text-danger';
            return $item;
        });
        $alunos->whereBetween('dias_validade', [8, 15])->map(function ($item, $key) {
            $item->class = 'text-warning';
            return $item;
        });
        $alunos->whereBetween('dias_validade', [16, 30])->map(function ($item, $key) {
            $item->class = 'text-info';
            return $item;
        });

        return view('dashboard.table', [
            'alunos' => $alunos,
            "page" => $request->input("page", 0),
            'filter_take' => $request->input('filter_take'),
        ]);
    }
}
