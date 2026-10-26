<?php

namespace App\Http\Controllers\Administracao;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\ConfiguracaoEleicao;
use App\Models\Eleitor;
use App\Models\Voto;
use Illuminate\View\View;

class ResultadoController extends Controller
{
    public function index(): View
    {
        $total = Voto::query()->count();
        $candidatos = Candidato::query()
            ->withCount(['votos' => fn ($query) => $query->where('tipo', Voto::TIPO_VALIDO)])
            ->orderByDesc('votos_count')
            ->orderBy('numero')
            ->get();

        return view('administracao.resultados', [
            'eleicao' => ConfiguracaoEleicao::atual(),
            'candidatos' => $candidatos,
            'total' => $total,
            'brancos' => Voto::query()->where('tipo', Voto::TIPO_BRANCO)->count(),
            'nulos' => Voto::query()->where('tipo', Voto::TIPO_NULO)->count(),
            'validos' => Voto::query()->where('tipo', Voto::TIPO_VALIDO)->count(),
            'totalEleitores' => Eleitor::query()->count(),
        ]);
    }
}
