@extends('layouts.app')

@section('titulo', 'Votação — Urna Escola')
@section('classe-conteudo', 'pagina-urna')

@section('conteudo')
    @include('componentes.erros')
    <div class="urna" data-consulta-url="{{ url('/urna/candidato') }}">
        <section class="urna-tela" aria-live="polite">
            <p class="urna-cabecalho">SEU VOTO PARA</p>
            <div id="estado-inicial">
                <h1>REPRESENTANTE</h1>
                <p>Digite os dois números do candidato.</p>
            </div>
            <div id="estado-candidato" class="oculto candidato-tela">
                <div>
                    <p>NÚMERO</p>
                    <div class="numero-tela"><span id="digito-1"></span><span id="digito-2"></span></div>
                    <p>Nome: <strong id="candidato-nome">—</strong></p>
                    <p>Partido: <strong id="candidato-partido">—</strong></p>
                </div>
                <div id="foto-candidato" class="foto-candidato">SEM FOTO</div>
            </div>
            <div id="estado-nulo" class="oculto mensagem-voto"><strong>VOTO NULO</strong><span>Número não encontrado</span></div>
            <div id="estado-branco" class="oculto mensagem-voto"><strong>VOTO EM BRANCO</strong></div>
            <div id="instrucao-confirmar" class="oculto instrucao-tela">Confira os dados e pressione <strong>CONFIRMA</strong>.</div>
        </section>

        <section class="urna-controles">
            <div class="identificacao-justica"><strong>URNA</strong><span>ESCOLA</span></div>
            <div class="teclado" aria-label="Teclado numérico">
                @foreach ([1,2,3,4,5,6,7,8,9] as $numero)
                    <button type="button" class="tecla tecla-numero" data-numero="{{ $numero }}">{{ $numero }}</button>
                @endforeach
                <span></span><button type="button" class="tecla tecla-numero" data-numero="0">0</button><span></span>
            </div>
            <div class="botoes-urna">
                <button type="button" id="botao-branco" class="tecla-acao branco">BRANCO</button>
                <button type="button" id="botao-corrige" class="tecla-acao corrige">CORRIGE</button>
                <button type="button" id="botao-confirma" class="tecla-acao confirma" disabled>CONFIRMA</button>
            </div>
        </section>
    </div>

    <div class="barra-eleitor">
        <span>Eleitor: <strong>{{ $eleitor->nome }}</strong></span>
        <form action="{{ route('urna.cancelar') }}" method="POST">
            @csrf
            <button class="link-botao" type="submit">Cancelar e sair</button>
        </form>
    </div>

    <form id="formulario-voto" action="{{ route('urna.confirmar') }}" method="POST" class="oculto">
        @csrf
        <input type="hidden" name="tipo" id="tipo-voto" value="numero">
        <input type="hidden" name="numero" id="numero-voto">
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('js/urna.js') }}"></script>
@endpush
