@foreach ($usuario->treinos as $treino)
    @include('exercicios.treino', [
        'treino' => $treino,
        'i' => $loop->index,
        'hidden' => false,
    ])
@endforeach
