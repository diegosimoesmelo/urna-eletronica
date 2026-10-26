<?php

namespace App\Http\Controllers\Administracao;

use App\Http\Controllers\Controller;
use App\Models\Eleitor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EleitorController extends Controller
{
    public function index(Request $request): View
    {
        $busca = trim((string) $request->query('busca'));

        $eleitores = Eleitor::query()
            ->when($busca, fn ($query) => $query->where('nome', 'like', "%{$busca}%")
                ->orWhere('codigo_acesso', 'like', "%{$busca}%"))
            ->orderBy('nome')
            ->paginate(12)
            ->withQueryString();

        return view('administracao.eleitores.index', compact('eleitores', 'busca'));
    }

    public function create(): View
    {
        return view('administracao.eleitores.formulario', [
            'eleitor' => new Eleitor,
            'codigoSugerido' => $this->gerarCodigo(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Eleitor::query()->create($this->validar($request));

        return redirect()->route('administracao.eleitores.index')
            ->with('sucesso', 'Eleitor cadastrado.');
    }

    public function edit(Eleitor $eleitor): View
    {
        return view('administracao.eleitores.formulario', [
            'eleitor' => $eleitor,
            'codigoSugerido' => $eleitor->codigo_acesso,
        ]);
    }

    public function update(Request $request, Eleitor $eleitor): RedirectResponse
    {
        $eleitor->update($this->validar($request, $eleitor));

        return redirect()->route('administracao.eleitores.index')
            ->with('sucesso', 'Eleitor atualizado.');
    }

    public function destroy(Eleitor $eleitor): RedirectResponse
    {
        if ($eleitor->jaVotou()) {
            return back()->with('erro', 'Não é possível excluir um eleitor que já votou.');
        }

        $eleitor->delete();

        return back()->with('sucesso', 'Eleitor excluído.');
    }

    private function validar(Request $request, ?Eleitor $eleitor = null): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'codigo_acesso' => [
                'required',
                'digits:6',
                Rule::unique('eleitores', 'codigo_acesso')->ignore($eleitor?->id),
            ],
        ], [
            'codigo_acesso.digits' => 'O código deve possuir exatamente 6 números.',
            'codigo_acesso.unique' => 'Este código já está em uso.',
        ]);
    }

    private function gerarCodigo(): string
    {
        do {
            $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Eleitor::query()->where('codigo_acesso', $codigo)->exists());

        return $codigo;
    }
}
