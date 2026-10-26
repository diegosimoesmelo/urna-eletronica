<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\ConfiguracaoEleicao;
use App\Models\Eleitor;
use App\Models\Voto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UrnaController extends Controller
{
    public function identificacao(): View
    {
        return view('urna.identificacao', ['eleicao' => ConfiguracaoEleicao::atual()]);
    }

    public function acessar(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'codigo_acesso' => ['required', 'digits:6'],
        ], [
            'codigo_acesso.required' => 'Informe o código de acesso.',
            'codigo_acesso.digits' => 'O código deve possuir 6 números.',
        ]);

        if (ConfiguracaoEleicao::atual()->status !== ConfiguracaoEleicao::STATUS_ABERTA) {
            return back()->withErrors(['codigo_acesso' => 'A votação não está aberta.']);
        }

        $eleitor = Eleitor::query()->where('codigo_acesso', $dados['codigo_acesso'])->first();

        if (! $eleitor) {
            return back()->withErrors(['codigo_acesso' => 'Código de acesso não encontrado.']);
        }

        if ($eleitor->jaVotou()) {
            return back()->withErrors(['codigo_acesso' => 'Este código já foi utilizado para votar.']);
        }

        $request->session()->regenerate();
        $request->session()->put('eleitor_id', $eleitor->id);

        return redirect()->route('urna.votacao');
    }

    public function votacao(Request $request): View|RedirectResponse
    {
        $eleitor = $this->eleitorDaSessao($request);

        if (! $eleitor || $eleitor->jaVotou()) {
            $request->session()->forget('eleitor_id');

            return redirect()->route('urna.identificacao')
                ->withErrors(['codigo_acesso' => 'Identifique-se para iniciar a votação.']);
        }

        if (ConfiguracaoEleicao::atual()->status !== ConfiguracaoEleicao::STATUS_ABERTA) {
            return redirect()->route('inicio')->with('erro', 'A votação não está aberta.');
        }

        return view('urna.votacao', ['eleitor' => $eleitor]);
    }

    public function consultarCandidato(Request $request, string $numero): JsonResponse
    {
        if (! $this->eleitorDaSessao($request) || ! preg_match('/^\d{2}$/', $numero)) {
            abort(403);
        }

        $candidato = Candidato::query()
            ->where('numero', $numero)
            ->where('ativo', true)
            ->first();

        if (! $candidato) {
            return response()->json(['encontrado' => false]);
        }

        return response()->json([
            'encontrado' => true,
            'nome' => $candidato->nome,
            'numero' => $candidato->numero,
            'partido' => $candidato->partido,
            'foto' => $candidato->foto ? asset('storage/'.$candidato->foto) : null,
        ]);
    }

    public function confirmar(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'tipo' => ['required', 'in:numero,branco'],
            'numero' => ['nullable', 'digits:2'],
        ]);

        if ($dados['tipo'] === 'numero' && empty($dados['numero'])) {
            throw ValidationException::withMessages(['numero' => 'Digite os dois números do candidato.']);
        }

        if (ConfiguracaoEleicao::atual()->status !== ConfiguracaoEleicao::STATUS_ABERTA) {
            return redirect()->route('inicio')->with('erro', 'A votação foi encerrada.');
        }

        DB::transaction(function () use ($request, $dados): void {
            $eleitorId = $request->session()->get('eleitor_id');
            $eleitor = Eleitor::query()->lockForUpdate()->find($eleitorId);

            if (! $eleitor || $eleitor->jaVotou()) {
                throw ValidationException::withMessages([
                    'voto' => 'Este eleitor não pode registrar outro voto.',
                ]);
            }

            $tipo = Voto::TIPO_BRANCO;
            $candidato = null;
            $numero = null;

            if ($dados['tipo'] === 'numero') {
                $numero = $dados['numero'];
                $candidato = Candidato::query()
                    ->where('numero', $numero)
                    ->where('ativo', true)
                    ->first();
                $tipo = $candidato ? Voto::TIPO_VALIDO : Voto::TIPO_NULO;
            }

            Voto::query()->create([
                'candidato_id' => $candidato?->id,
                'tipo' => $tipo,
                'numero_digitado' => $numero,
            ]);

            $eleitor->update(['votou_em' => now()]);
        });

        $request->session()->forget('eleitor_id');

        return redirect()->route('urna.concluida');
    }

    public function concluida(): View
    {
        return view('urna.concluida');
    }

    public function cancelar(Request $request): RedirectResponse
    {
        $request->session()->forget('eleitor_id');

        return redirect()->route('inicio');
    }

    private function eleitorDaSessao(Request $request): ?Eleitor
    {
        $id = $request->session()->get('eleitor_id');

        return $id ? Eleitor::query()->find($id) : null;
    }
}
