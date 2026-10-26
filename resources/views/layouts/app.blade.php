<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Urna Escola')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="topo">
        <a class="marca" href="{{ route('inicio') }}">
            <span class="marca-simbolo" aria-hidden="true">UE</span>
            <span>Urna Escola<small>Projeto educacional</small></span>
        </a>
        <nav class="navegacao" aria-label="Navegação principal">
            <a href="{{ route('inicio') }}">Início</a>
            @auth
                <a href="{{ route('administracao.painel') }}">Administração</a>
                <form action="{{ route('administracao.sair') }}" method="POST">
                    @csrf
                    <button class="link-botao" type="submit">Sair</button>
                </form>
            @else
                <a href="{{ route('administracao.entrar') }}">Área administrativa</a>
            @endauth
        </nav>
    </header>

    <main class="conteudo @yield('classe-conteudo')">
        @if (session('sucesso'))
            <div class="alerta alerta-sucesso" role="status">{{ session('sucesso') }}</div>
        @endif
        @if (session('erro'))
            <div class="alerta alerta-erro" role="alert">{{ session('erro') }}</div>
        @endif
        @yield('conteudo')
    </main>

    <footer class="rodape">
        Simulação criada para fins educacionais — não utilizar em eleições oficiais.
    </footer>
    @stack('scripts')
</body>
</html>
