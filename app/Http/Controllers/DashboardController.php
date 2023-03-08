<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Certificado;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        $certificados = Certificado::where('empresa_id', session()->get('empresa_id'))->get();
        return view('dashboard.index', [
            'certificados' => $certificados
        ]);
    }
}
