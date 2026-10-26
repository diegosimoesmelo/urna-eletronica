@extends('layouts.administracao')

@php($editando = $eleitor->exists)
@section('titulo', $editando ? 'Editar eleitor' : 'Novo eleitor')

@section('admin-conteudo')
    <div class="cabecalho-pagina">
        <div><span class="etiqueta">ELEITORES</span><h1>{{ $editando ? 'Editar eleitor' : 'Novo eleitor' }}</h1><p>Use um código de seis dígitos que seja único.</p></div>
        <a class="botao botao-secundario" href="{{ route('administracao.eleitores.index') }}">Voltar</a>
    </div>
    <section class="cartao">
        @include('componentes.erros')
        <form action="{{ $editando ? route('administracao.eleitores.update', $eleitor) : route('administracao.eleitores.store') }}" method="POST" class="formulario">
            @csrf
            @if ($editando) @method('PUT') @endif
            <div class="grade-formulario">
                <label>Nome do eleitor *
                    <input type="text" name="nome" value="{{ old('nome', $eleitor->nome) }}" maxlength="120" required autofocus>
                </label>
                <label>Código de acesso *
                    <input class="campo-codigo-menor" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" name="codigo_acesso" value="{{ old('codigo_acesso', $codigoSugerido) }}" required>
                </label>
            </div>
            @if ($eleitor->jaVotou())
                <div class="alerta alerta-info">Este eleitor já votou. Alterar seus dados não modifica nem identifica o voto registrado.</div>
            @endif
            <div class="acoes-formulario"><button class="botao botao-primario" type="submit">Salvar eleitor</button></div>
        </form>
    </section>
@endsection
