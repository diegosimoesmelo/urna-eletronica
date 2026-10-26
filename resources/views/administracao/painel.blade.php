@extends('layouts.administracao')

@section('titulo', 'Painel administrativo')

@section('admin-conteudo')
    <div class="cabecalho-pagina">
        <div><span class="etiqueta">PAINEL</span><h1>Visão geral</h1><p>Acompanhe e configure a simulação.</p></div>
        <a class="botao botao-secundario" href="{{ route('inicio') }}" target="_blank">Abrir página pública</a>
    </div>

    <div class="grade-indicadores">
        <article><span>Candidatos</span><strong>{{ $totalCandidatos }}</strong></article>
        <article><span>Eleitores</span><strong>{{ $totalEleitores }}</strong></article>
        <article><span>Votos registrados</span><strong>{{ $totalVotos }}</strong></article>
        <article><span>Ainda não votaram</span><strong>{{ $eleitoresPendentes }}</strong></article>
    </div>

    <section class="cartao">
        <div class="titulo-com-estado">
            <div><h2>Configuração da eleição</h2><p>Defina o título e controle quando a urna aceita votos.</p></div>
            <span class="estado estado-{{ $eleicao->status }}">{{ ucfirst($eleicao->status) }}</span>
        </div>
        @include('componentes.erros')
        <form action="{{ route('administracao.eleicao.atualizar') }}" method="POST" class="formulario formulario-horizontal">
            @csrf
            @method('PUT')
            <label>Título da eleição
                <input type="text" name="titulo" value="{{ old('titulo', $eleicao->titulo) }}" maxlength="120" required>
            </label>
            <label>Situação
                <select name="status" required>
                    <option value="configuracao" @selected($eleicao->status === 'configuracao')>Em configuração</option>
                    <option value="aberta" @selected($eleicao->status === 'aberta')>Votação aberta</option>
                    <option value="encerrada" @selected($eleicao->status === 'encerrada')>Votação encerrada</option>
                </select>
            </label>
            <button class="botao botao-primario" type="submit">Salvar configuração</button>
        </form>
    </section>
@endsection
