@if ($registros->hasPages())
    <nav class="paginacao" aria-label="Paginação">
        @if ($registros->onFirstPage())
            <span>← Anterior</span>
        @else
            <a href="{{ $registros->previousPageUrl() }}">← Anterior</a>
        @endif
        <span>Página {{ $registros->currentPage() }} de {{ $registros->lastPage() }}</span>
        @if ($registros->hasMorePages())
            <a href="{{ $registros->nextPageUrl() }}">Próxima →</a>
        @else
            <span>Próxima →</span>
        @endif
    </nav>
@endif
