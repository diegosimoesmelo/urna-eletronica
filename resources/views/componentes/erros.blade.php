@if ($errors->any())
    <div class="alerta alerta-erro" role="alert">
        <strong>Verifique os campos:</strong>
        <ul>
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif
