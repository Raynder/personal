<x-external-layout>
    <div class="card overflow-hidden">
        <!-- Help Center Header -->
        <div class="help-center-header d-flex flex-column justify-content-center align-items-center">
            <h3 class="zindex-1 text-center">Olá {{ session()->get('ename') }}.</h3>

            <p class="zindex-1 text-center px-3 mb-0 mt-3">
                Vamos lhe ajudar a incluir seu certificado.
                <br />Clique abaixo para iniciarmos.
            </p>
            <p class="zindex-1 text-center px-3 mb-0 mt-3">
                <button class="btn btn-primary btn-lg"
                    onclick="window.location.href='{{ route('external.customer.create') }}'">
                    <i class="fa fa-plus" style="margin-right: 10px;"></i> Cadastrar meu certificado
                </button>
            </p>
        </div>
        <!-- /Help Center Header -->

    </div>
    <div class="row h-100">
        <div class="d-flex align-items-center justify-content-center">
            <div class="flex">

                <div class="text-center">

                </div>
            </div>
        </div>
    </div>
    @push('page_scripts')
        <script>
            function atualizarTela() {
                window.location.reload();
            }
        </script>
    @endpush
</x-external-layout>
