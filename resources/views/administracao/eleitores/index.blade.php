@extends('layouts.administracao')

@section('titulo', 'Eleitores')

@section('admin-conteudo')
    <div class="cabecalho-pagina">
        <div><span class="etiqueta">CADASTROS</span><h1>Eleitores</h1><p>Cada código permite registrar apenas um voto.</p></div>
        <a class="botao botao-primario" href="{{ route('administracao.eleitores.create') }}">Novo eleitor</a>
    </div>

    <form method="GET" class="busca">
        <input type="search" name="busca" value="{{ $busca }}" placeholder="Buscar por nome ou código">
        <button class="botao botao-secundario" type="submit">Buscar</button>
    </form>

    <section class="cartao tabela-responsiva">
        @if ($eleitores->isEmpty())
            <div class="estado-vazio"><h2>Nenhum eleitor encontrado</h2><p>Cadastre eleitores ou altere sua busca.</p></div>
        @else
            <table>
                <thead><tr><th>Nome</th><th>Código de acesso</th><th>Situação</th><th>Horário do voto</th><th class="alinha-direita">Ações</th></tr></thead>
                <tbody>
                    @foreach ($eleitores as $eleitor)
                        <tr>
                            <td><strong>{{ $eleitor->nome }}</strong></td>
                            <td><code class="codigo-acesso">{{ $eleitor->codigo_acesso }}</code></td>
                            <td><span class="estado estado-{{ $eleitor->jaVotou() ? 'aberta' : 'configuracao' }}">{{ $eleitor->jaVotou() ? 'Já votou' : 'Pendente' }}</span></td>
                            <td>{{ $eleitor->votou_em?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="acoes-tabela">
                                <a href="{{ route('administracao.eleitores.edit', $eleitor) }}">Editar</a>
                                @unless ($eleitor->jaVotou())
                                    <form action="{{ route('administracao.eleitores.destroy', $eleitor) }}" method="POST" onsubmit="return confirm('Deseja excluir este eleitor?')">
                                        @csrf @method('DELETE')<button type="submit">Excluir</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('componentes.paginacao', ['registros' => $eleitores])
        @endif
    </section>
@endsection
