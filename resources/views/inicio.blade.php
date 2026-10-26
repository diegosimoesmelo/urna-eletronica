@extends('layouts.app')

@section('titulo', 'Urna Escola — Início')

@section('conteudo')
    <section class="hero">
        <div>
            <span class="etiqueta">SIMULAÇÃO EDUCACIONAL</span>
            <h1>{{ $eleicao->titulo }}</h1>
            <p>Uma experiência simples para aprender cidadania e desenvolvimento web com segurança e transparência.</p>
            <div class="acoes">
                @if ($eleicao->status === 'aberta')
                    <a class="botao botao-primario" href="{{ route('urna.identificacao') }}">Iniciar votação</a>
                @elseif ($eleicao->status === 'encerrada')
                    <span class="estado estado-encerrada">Votação encerrada</span>
                @else
                    <span class="estado">Votação em preparação</span>
                @endif
                <a class="botao botao-secundario" href="{{ route('administracao.entrar') }}">Acessar administração</a>
            </div>
        </div>
        <div class="hero-ilustracao" aria-hidden="true">
            <div class="urna-mini">
                <div class="urna-mini-tela">SEU VOTO<br><strong>É SECRETO</strong></div>
                <div class="urna-mini-teclas">1 2 3<br>4 5 6<br>7 8 9<br>0</div>
            </div>
        </div>
    </section>

    <section class="passos">
        <article><span>1</span><h2>Identifique-se</h2><p>Use o código fornecido pelo professor.</p></article>
        <article><span>2</span><h2>Escolha</h2><p>Digite o número ou vote em branco.</p></article>
        <article><span>3</span><h2>Confirme</h2><p>Confira os dados e registre seu voto.</p></article>
    </section>
@endsection
