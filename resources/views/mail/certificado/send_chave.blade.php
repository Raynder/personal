@component('mail::message')

    # Olá!!

    Chave para instalação do certificado: {{ $acesso->certificado->fantasia }}.

    Chave: {{ $acesso->chave }}
    
    
    @component('mail::button', ['url' => url("https://www.flybisistemas.com.br/dw/FlyTokenSetup.exe")])
        Download da aplicação
    @endcomponent

    <p>Obrigado</p>

@endcomponent