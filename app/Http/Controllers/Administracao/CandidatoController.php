<?php

namespace App\Http\Controllers\Administracao;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CandidatoController extends Controller
{
    public function index(): View
    {
        return view('administracao.candidatos.index', [
            'candidatos' => Candidato::query()->orderBy('numero')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('administracao.candidatos.formulario', ['candidato' => new Candidato]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validar($request);
        $dados['ativo'] = $request->boolean('ativo');

        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('candidatos', 'public');
        }

        Candidato::query()->create($dados);

        return redirect()->route('administracao.candidatos.index')
            ->with('sucesso', 'Candidato cadastrado.');
    }

    public function edit(Candidato $candidato): View
    {
        return view('administracao.candidatos.formulario', compact('candidato'));
    }

    public function update(Request $request, Candidato $candidato): RedirectResponse
    {
        $dados = $this->validar($request, $candidato);
        $dados['ativo'] = $request->boolean('ativo');

        if ($request->hasFile('foto')) {
            if ($candidato->foto) {
                Storage::disk('public')->delete($candidato->foto);
            }
            $dados['foto'] = $request->file('foto')->store('candidatos', 'public');
        }

        $candidato->update($dados);

        return redirect()->route('administracao.candidatos.index')
            ->with('sucesso', 'Candidato atualizado.');
    }

    public function destroy(Candidato $candidato): RedirectResponse
    {
        if ($candidato->votos()->exists()) {
            return back()->with('erro', 'Não é possível excluir um candidato que já recebeu votos. Desative-o.');
        }

        if ($candidato->foto) {
            Storage::disk('public')->delete($candidato->foto);
        }
        $candidato->delete();

        return back()->with('sucesso', 'Candidato excluído.');
    }

    private function validar(Request $request, ?Candidato $candidato = null): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'numero' => ['required', 'digits:2', Rule::unique('candidatos', 'numero')->ignore($candidato?->id)],
            'partido' => ['required', 'string', 'max:80'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ], [
            'numero.unique' => 'Este número já pertence a outro candidato.',
            'numero.digits' => 'O número deve possuir exatamente 2 dígitos.',
            'foto.image' => 'O arquivo enviado deve ser uma imagem.',
            'foto.max' => 'A imagem deve ter no máximo 2 MB.',
        ]);
    }
}
