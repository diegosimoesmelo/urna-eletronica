<?php

namespace App\Http\Controllers\Administracao;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\ConfiguracaoEleicao;
use App\Models\Eleitor;
use App\Models\Voto;
use Illuminate\View\View;

class PainelController extends Controller
{
    public function index(): View
    {
        return view('administracao.painel', [
            'eleicao' => ConfiguracaoEleicao::atual(),
            'totalCandidatos' => Candidato::query()->count(),
            'totalEleitores' => Eleitor::query()->count(),
            'totalVotos' => Voto::query()->count(),
            'eleitoresPendentes' => Eleitor::query()->whereNull('votou_em')->count(),
        ]);
    }
}
