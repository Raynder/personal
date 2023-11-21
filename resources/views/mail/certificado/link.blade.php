@component('mail::message')

    # Olá!!

    Você está recebendo o link para cadastrar o certificado de sua empresa.
    Se não for você ({{ $name }}), por favor, ignore este e-mail.

    @component('mail::button', ['url' => route('external.customer.index') . '?t=' . $token])
        Abrir
    @endcomponent

    <p>Obrigado</p>

@endcomponent
