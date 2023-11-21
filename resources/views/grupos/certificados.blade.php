@foreach ($grupo->certificados as $certificado)
    @include('grupos.certificado', [
        'certificado' => $certificado,
        'i' => $loop->index,
        'hidden' => false,
    ])
@endforeach
