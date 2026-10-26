@extends('layouts.administracao')

@section('titulo', 'Candidatos')

@section('admin-conteudo')
    <div class="cabecalho-pagina">
        <div><span class="etiqueta">CADASTROS</span><h1>Candidatos</h1><p>Gerencie as opções disponíveis na urna.</p></div>
        <a class="botao botao-primario" href="{{ route('administracao.candidatos.create') }}">Novo candidato</a>
    </div>

    <section class="cartao tabela-responsiva">
        @if ($candidatos->isEmpty())
            <div class="estado-vazio"><h2>Nenhum candidato cadastrado</h2><p>Cadastre o primeiro candidato para começar.</p></div>
        @else
            <table>
                <thead><tr><th>Número</th><th>Candidato</th><th>Partido</th><th>Situação</th><th class="alinha-direita">Ações</th></tr></thead>
                <tbody>
                    @foreach ($candidatos as $candidato)
                        <tr>
                            <td><span class="numero-candidato">{{ $candidato->numero }}</span></td>
                            <td><div class="celula-pessoa">
                                @if ($candidato->foto)<img src="{{ asset('storage/'.$candidato->foto) }}" alt="">@else<span>{{ mb_substr($candidato->nome, 0, 1) }}</span>@endif
                                <strong>{{ $candidato->nome }}</strong>
                            </div></td>
                            <td>{{ $candidato->partido }}</td>
                            <td><span class="estado estado-{{ $candidato->ativo ? 'aberta' : 'encerrada' }}">{{ $candidato->ativo ? 'Ativo' : 'Inativo' }}</span></td>
                            <td class="acoes-tabela">
                                <a href="{{ route('administracao.candidatos.edit', $candidato) }}">Editar</a>
                                <form action="{{ route('administracao.candidatos.destroy', $candidato) }}" method="POST" onsubmit="return confirm('Deseja excluir este candidato?')">
                                    @csrf @method('DELETE')
                                    <button type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('componentes.paginacao', ['registros' => $candidatos])
        @endif
    </section>
@endsection
