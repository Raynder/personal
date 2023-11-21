<?php

namespace App\Http\Controllers;

use App\Helpers\FormatterHelper;
use App\Http\Controllers\Controller;
use App\Models\Certificado;
use App\Models\Grupo;
use App\Models\Usuario;
use App\Repositories\CertificadoRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $certificadoRepository;

    public function __construct(CertificadoRepository $certificadoRepo)
    {
        $this->certificadoRepository = $certificadoRepo;
    }

    public function index()
    {
        $params = [
        ];
        $certificados = $this->certificadoRepository->validate($params);
        $usuarios = Usuario::where('empresa_id', session()->get('empresa_id'))->get();
        $grupos = Grupo::where('empresa_id', session()->get('empresa_id'))->get();

        $certificados = $certificados->map(function ($item, $key) {
            $item->data_validade = Carbon::parse($item->data_validade);
            $item->dias_validade = $item->data_validade->diffInDays(Carbon::now());
            return $item;
        });
        
        $certificados7 = $certificados->whereBetween('dias_validade', [1, 7]);
        $certificados15 = $certificados->whereBetween('dias_validade', [8, 15]);
        $certificados30 = $certificados->whereBetween('dias_validade', [16, 31]);
        
        return view('dashboard.index', [
            'certificados' => $certificados,
            'usuarios' => $usuarios,
            'grupos' => $grupos,
            'certificados7' => $certificados7,
            'certificados15' => $certificados15,
            'certificados30' => $certificados30,
        ]);
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        if (isset($searchData['filter_cnpj'])) {
            $searchData['filter_cnpj'] = FormatterHelper::onlyNumbers($searchData['filter_cnpj']);
        }
        $certificados = $this->certificadoRepository->validate($searchData);
        $certificados->map(function ($item, $key) {
            $item->data_validade = Carbon::parse($item->data_validade);
            $item->dias_validade = $item->data_validade->diffInDays(Carbon::now());
            return $item;
        });
    
        $certificados->whereBetween('dias_validade', [0, 7])->map(function ($item, $key) {
            $item->class = 'text-danger';
            return $item;
        });
        $certificados->whereBetween('dias_validade', [8, 15])->map(function ($item, $key) {
            $item->class = 'text-warning';
            return $item;
        });
        $certificados->whereBetween('dias_validade', [16, 30])->map(function ($item, $key) {
            $item->class = 'text-info';
            return $item;
        });

        return view('dashboard.table', [
            'certificados' => $certificados,
            "page" => $request->input("page", 0),
            'filter_take' => $request->input('filter_take'),
        ]);
    }
}
