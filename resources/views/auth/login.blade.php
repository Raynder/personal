<x-guest-layout>
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center">
                <div class="flex-row text-center mx-auto">
                    <img src="{{ asset('img/logomarca.png') }}" alt="Auth Cover Bg color"
                        class="img-fluid authentication-cover-img" data-app-light-img="pages/login-light.png"
                        data-app-dark-img="pages/login-dark.png" style="max-width: 300px;" />
                    <div class="mx-auto">
                        <h3>Descubra o que o FlyToken pode fazer por você 🥳</h3>
                        <p>
                            Tenha controle total de quem utiliza seus certificados e quem continuará utilizando.
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
                                <img src="{{ asset('img/logo.png') }}" alt="Logo Sistema" width="50"
                                    class="img-fluid" />
                            </span>
                            <span class="app-brand-text demo h3 fw-bold mb-0">FlyToken</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Seja bem-vindo ao ambiente Administrativo! 👋</h4>
                    <p class="mb-4">Informe seus dados para acessar a plataforma</p>
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    <form class="mb-3" action="{{ route('login') }}" method="POST">
                        <input type="hidden" name="recaptcha" id="recaptcha" />
                        @csrf
                        <div class="mb-3">
                            <x-label for="email" :value="__('E-mail')" />

                            <x-input id="email" class="" type="email" name="email" :value="old('email')"
                                required autofocus />
                        </div>
                        <div class="form-password-toggle mb-3">
                            <div class="d-flex justify-content-between">
                                <x-label for="password" :value="__('Senha')" />
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" style="margin-right: 15px;"
                                        tabindex="-1">
                                        <small>Esqueceu a senha?</small>
                                    </a>
                                @endif
                            </div>
                            <div class="input-group input-group-merge">
                                <x-input id="password" class="" type="password" name="password"
                                    aria-describedby="password" required autocomplete="current-password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <x-button class="btn btn-primary d-grid w-100">
                            Entrar
                        </x-button>
                    </form>

                    <p class="text-center">
                        <span>Aplicação Desktop</span>
                        <a href="https://www.flybisistemas.com.br/dw/FlyTokenSetup.exe" tabindex="-1">
                            <span>Baixe aqui</span>
                        </a>
                    </p>

                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
    @push('page_scripts')
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('G_SITE_KEY') }}"></script>
        <script>
            grecaptcha.ready(() => {
                grecaptcha.execute('{{ env('G_SITE_KEY') }}', {
                    action: 'contact'
                }).then(token => {
                    document.querySelector('#recaptcha').value = token;
                });
            });
        </script>
    @endpush
</x-guest-layout>
