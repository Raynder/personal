@component('mail::message')

    # Olá!!

    Chave para instalação dos seguintes certificados:
        
    @foreach($certificado as $cert)
        Certificado: {{ $cert->razao_social }}.
    @endforeach

    Chave: {{ $acesso->chave }}
    
    
    @component('mail::button', ['url' => url("https://www.bytoken.com.br/dw/ByTokenSetup.exe")])
        Download da aplicação
    @endcomponent

    <p>Obrigado</p>

@endcomponent