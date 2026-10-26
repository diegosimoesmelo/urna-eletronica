<?php

namespace App\Http\Controllers\Administracao;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoEleicao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EleicaoController extends Controller
{
    public function atualizar(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:120'],
            'status' => ['required', 'in:configuracao,aberta,encerrada'],
        ]);

        ConfiguracaoEleicao::atual()->update($dados);

        return back()->with('sucesso', 'Configurações da eleição atualizadas.');
    }
}
