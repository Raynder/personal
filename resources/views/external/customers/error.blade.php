<x-external-layout>
    <div class="card overflow-hidden">
        <!-- Help Center Header -->
        <div class="help-center-header d-flex flex-column justify-content-center align-items-center">
            <h3 class="zindex-1 text-center">Um problema ocorreu ao completar sua solicitação.</h3>
            <h6 class="zindex-1 text-center">Veja o motivo abaixo.</h6>

            <h1 class="zindex-1 text-center px-3 mb-0 mt-3 text-xl">
                {{ session('message') }}
            </h1>
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
</x-external-layout>
