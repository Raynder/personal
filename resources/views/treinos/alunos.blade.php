@foreach ($treino->alunos as $aluno)
    @include('treinos.aluno', [
        'aluno' => $aluno,
        'i' => $loop->index,
        'hidden' => false,
    ])
@endforeach
