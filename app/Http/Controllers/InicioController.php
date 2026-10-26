<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracaoEleicao;
use Illuminate\View\View;

class InicioController extends Controller
{
    public function index(): View
    {
        return view('inicio', ['eleicao' => ConfiguracaoEleicao::atual()]);
    }
}
