@component('mail::message')

    # Olá {{ $user->name }}

    Identificamos que você possui tarefas atrasadas.
    Acesse o sistema para conferir!

    @component('mail::button', ['url' => url('/')])
        Acessar FlyTask
    @endcomponent

    Obrigado

@endcomponent
