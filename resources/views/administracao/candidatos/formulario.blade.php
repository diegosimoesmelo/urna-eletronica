@extends('layouts.administracao')

@php($editando = $candidato->exists)
@section('titulo', $editando ? 'Editar candidato' : 'Novo candidato')

@section('admin-conteudo')
    <div class="cabecalho-pagina">
        <div><span class="etiqueta">CANDIDATOS</span><h1>{{ $editando ? 'Editar candidato' : 'Novo candidato' }}</h1><p>Os campos com asterisco são obrigatórios.</p></div>
        <a class="botao botao-secundario" href="{{ route('administracao.candidatos.index') }}">Voltar</a>
    </div>
    <section class="cartao">
        @include('componentes.erros')
        <form action="{{ $editando ? route('administracao.candidatos.update', $candidato) : route('administracao.candidatos.store') }}" method="POST" enctype="multipart/form-data" class="formulario">
            @csrf
            @if ($editando) @method('PUT') @endif
            <div class="grade-formulario">
                <label class="campo-amplo">Nome completo *
                    <input type="text" name="nome" value="{{ old('nome', $candidato->nome) }}" maxlength="120" required autofocus>
                </label>
                <label>Número (2 dígitos) *
                    <input type="text" inputmode="numeric" pattern="[0-9]{2}" maxlength="2" name="numero" value="{{ old('numero', $candidato->numero) }}" required>
                </label>
                <label>Partido ou grupo *
                    <input type="text" name="partido" value="{{ old('partido', $candidato->partido) }}" maxlength="80" required>
                </label>
                <label class="campo-amplo">Foto (JPG, PNG ou WEBP, até 2 MB)
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp">
                </label>
                <label class="checkbox campo-amplo"><input type="checkbox" name="ativo" value="1" @checked(old('ativo', $candidato->exists ? $candidato->ativo : true))> Candidato disponível para votação</label>
            </div>
            <div class="acoes-formulario"><button class="botao botao-primario" type="submit">Salvar candidato</button></div>
        </form>
    </section>
@endsection
