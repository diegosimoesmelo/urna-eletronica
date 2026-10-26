@extends('layouts.app')

@section('titulo', 'Identificação do eleitor')

@section('conteudo')
    <section class="cartao cartao-login centralizado">
        <span class="etiqueta">{{ $eleicao->titulo }}</span>
        <h1>Identificação do eleitor</h1>
        @if ($eleicao->status === 'aberta')
            <p>Digite o código de 6 números entregue pelo responsável pela eleição.</p>
            @include('componentes.erros')
            <form action="{{ route('urna.acessar') }}" method="POST" class="formulario">
                @csrf
                <label>Código de acesso
                    <input class="campo-codigo" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" name="codigo_acesso" value="{{ old('codigo_acesso') }}" required autofocus autocomplete="off">
                </label>
                <button class="botao botao-primario largura-total" type="submit">Continuar</button>
            </form>
        @else
            <div class="alerta alerta-info">A votação não está aberta neste momento.</div>
            <a class="botao botao-secundario" href="{{ route('inicio') }}">Voltar ao início</a>
        @endif
    </section>
@endsection
