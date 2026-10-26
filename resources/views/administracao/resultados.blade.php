@extends('layouts.administracao')

@section('titulo', 'Apuração')

@section('admin-conteudo')
    <div class="cabecalho-pagina">
        <div><span class="etiqueta">APURAÇÃO</span><h1>{{ $eleicao->titulo }}</h1><p>Resultados atualizados a cada recarregamento.</p></div>
        <button class="botao botao-secundario" type="button" onclick="window.print()">Imprimir relatório</button>
    </div>

    @if ($eleicao->status !== 'encerrada')
        <div class="alerta alerta-info">A eleição ainda não foi encerrada. Estes resultados são parciais e visíveis apenas para administradores.</div>
    @endif

    <div class="grade-indicadores">
        <article><span>Total de votos</span><strong>{{ $total }}</strong></article>
        <article><span>Votos válidos</span><strong>{{ $validos }}</strong></article>
        <article><span>Em branco</span><strong>{{ $brancos }}</strong></article>
        <article><span>Nulos</span><strong>{{ $nulos }}</strong></article>
    </div>

    <section class="cartao">
        <h2>Resultado por candidato</h2>
        <div class="lista-resultados">
            @forelse ($candidatos as $candidato)
                @php($percentual = $validos > 0 ? ($candidato->votos_count / $validos) * 100 : 0)
                <article class="resultado-candidato">
                    <div class="resultado-identidade">
                        <span class="numero-candidato">{{ $candidato->numero }}</span>
                        <div><strong>{{ $candidato->nome }}</strong><small>{{ $candidato->partido }}</small></div>
                    </div>
                    <div class="resultado-numeros"><strong>{{ $candidato->votos_count }}</strong><span>{{ number_format($percentual, 1, ',', '.') }}%</span></div>
                    <div class="barra-resultado"><span style="width: {{ $percentual }}%"></span></div>
                </article>
            @empty
                <div class="estado-vazio"><p>Nenhum candidato cadastrado.</p></div>
            @endforelse
        </div>
    </section>

    <p class="nota-relatorio">Participação: {{ $total }} de {{ $totalEleitores }} eleitores ({{ $totalEleitores > 0 ? number_format(($total / $totalEleitores) * 100, 1, ',', '.') : '0,0' }}%).</p>
@endsection
