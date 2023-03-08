<x-guest-layout>
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center">
                <div class="flex-row text-center mx-auto">
                    <img
                        src="{{ asset('img/logomarca.png') }}"
                        alt="Auth Cover Bg color"
                        class="img-fluid authentication-cover-img"
                        data-app-light-img="pages/login-light.png"
                        data-app-dark-img="pages/login-dark.png"
                        style="max-width: 300px;"
                    />
                    <div class="mx-auto">
                        <h3>Esqueceu sua senha? Não se preocupe.</h3>
                        <p>
                            Informe seu e-mail abaixo e enviaremos um e-mail com as orientações para recriar sua senha.
                        </p>
                    </div>
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="authentication-bg d-flex col-12 col-lg-5 col-xl-4 align-items-center p-4 p-sm-5">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="#" class="app-brand-link gap-2 mb-2">
                            <span class="app-brand-logo demo">
                                <img
                                    src="{{ asset('img/logo.png') }}"
                                    alt="Logo Sistema"
                                    width="50"
                                    class="img-fluid"
                                />
                            </span>
                            <span class="app-brand-text demo h3 fw-bold mb-0">FlyToken</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Sua nova fase começa aqui! 🚀</h4>
                    <p class="mb-4">Informe seus dados para acessar a plataforma</p>
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    <form class="mb-3" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <x-label for="email" :value="__('E-mail')" />

                            <x-input id="email" class="" type="email" name="email" :value="old('email')" required autofocus />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <x-button class="btn btn-primary d-grid w-100">
                                {{ __('Confirmar') }}
                            </x-button>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <a class="text-muted" href="{{ route('login') }}" style="margin-right: 15px; margin-top: 15px;">
                                {{ __('Retornar a tela de login') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
</x-guest-layout>
