@extends('layouts.app')

@section('titulo', 'Entrar — Administração')

@section('conteudo')
    <section class="cartao cartao-login">
        <span class="etiqueta">ACESSO RESTRITO</span>
        <h1>Área administrativa</h1>
        <p>Entre para cadastrar candidatos, eleitores e acompanhar a apuração.</p>
        @include('componentes.erros')
        <form action="{{ route('administracao.autenticar') }}" method="POST" class="formulario">
            @csrf
            <label>E-mail
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </label>
            <label>Senha
                <input type="password" name="password" required autocomplete="current-password">
            </label>
            <label class="checkbox"><input type="checkbox" name="lembrar" value="1"> Manter conectado</label>
            <button class="botao botao-primario largura-total" type="submit">Entrar</button>
        </form>
    </section>
@endsection
