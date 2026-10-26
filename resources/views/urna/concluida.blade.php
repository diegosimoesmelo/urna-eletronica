@extends('layouts.app')

@section('titulo', 'Voto registrado')

@section('conteudo')
    <section class="cartao confirmacao-final centralizado">
        <div class="icone-sucesso">✓</div>
        <h1>FIM</h1>
        <p>Seu voto foi registrado com sucesso.</p>
        <p class="texto-suave">A sessão foi encerrada e o voto permanece secreto.</p>
        <a class="botao botao-primario" href="{{ route('inicio') }}">Concluir</a>
    </section>
@endsection
