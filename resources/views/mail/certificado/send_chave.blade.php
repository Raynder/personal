@component('mail::message')

    # Olá!!

    Chave para instalação do certificado: {{ $acesso->certificado->razao_social }}.

    Chave: {{ $acesso->chave }}  
    
    
    @component('mail::button', ['url' => url("https://www.bytoken.com.br/dw/ByTokenSetup.exe")])
        Download da aplicação
    @endcomponent

    <p>Obrigado</p>

@endcomponent