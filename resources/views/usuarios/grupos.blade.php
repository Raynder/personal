@foreach ($usuario->grupos as $grupo)
    @include('usuarios.grupo', [
        'grupo' => $grupo,
        'i' => $loop->index,
        'hidden' => false,
    ])
@endforeach
