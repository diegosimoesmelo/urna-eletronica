@extends('layouts.app')

@section('classe-conteudo', 'conteudo-admin')

@section('conteudo')
    <div class="admin-layout">
        <aside class="menu-admin">
            <p class="menu-titulo">Administração</p>
            <a class="{{ request()->routeIs('administracao.painel') ? 'ativo' : '' }}" href="{{ route('administracao.painel') }}">Visão geral</a>
            <a class="{{ request()->routeIs('administracao.candidatos.*') ? 'ativo' : '' }}" href="{{ route('administracao.candidatos.index') }}">Candidatos</a>
            <a class="{{ request()->routeIs('administracao.eleitores.*') ? 'ativo' : '' }}" href="{{ route('administracao.eleitores.index') }}">Eleitores</a>
            <a class="{{ request()->routeIs('administracao.resultados') ? 'ativo' : '' }}" href="{{ route('administracao.resultados') }}">Apuração</a>
        </aside>
        <section class="admin-conteudo">
            @yield('admin-conteudo')
        </section>
    </div>
@endsection
