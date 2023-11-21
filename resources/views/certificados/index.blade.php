<x-app-layout>
    @if (count($certificados) > 0)
        <h4 class="breadcrumb-wrapper">
            <div class="breadcrumb-pre-title">Dashboard</div>
            <div class="breadcrumb-title">Certificados</div>
        </h4>
        <div class="content">

            @include('flash::message')

            <div class="card">
                <div class="card-header">
                    <form name="formSearch" id="formSearch" method="post" action="{{ route('certificados.search') }}">
                        @csrf
                        {!! Form::hidden('page', 0, ['id' => 'page']) !!}
                        {!! Form::hidden('filter_sort', 'id') !!}
                        {!! Form::hidden('filter_order', 'desc') !!}
                        {!! Form::hidden('filter_take', '10', ['id' => 'filter_take']) !!}
                        <div class="d-md-flex justify-content-between">
                            <div class="d-flex justify-content-aroud">
                                <div class="d-none d-md-block" style="margin-right: 15px;min-width: 300px;">
                                    <input type="text" class="form-control" placeholder="00.000.000/0000-00"
                                        name="filter_cnpj">
                                </div>
                                <div class="" style="min-width: 300px;">
                                    <input type="text" class="form-control" placeholder="Razão/Fantasia"
                                        name="filter_razao_social">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2" style="min-width: 300px;">
                                <button type="submit" class="btn btn-sm btn-secondary" id="orederStatistics">
                                    <i class="fa fa-search"></i> Pesquisar
                                </button>
                                <button type="button" onclick="executarLista('Gerar chave');"
                                    class="btn btn-sm btn-info btn-more-filter" id="btnLote"filter_sort
                                    data-bs-toggle="tooltip" data-color="primary" data-bs-placement="top"
                                    data-bs-original-title="Enviar vários certificados em lote">
                                    <i class="fa fa-list-ol"></i>
                                </button>
                                <div class="d-none d-md-block"
                                    style="border-right: 1px solid silver; margin-right: 10px; margin-left: 10px;">
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
                                    data-color="primary" data-bs-placement="top"
                                    data-bs-original-title="Enviar link para o cliente cadastrar o certificado"
                                    onclick="Tela.abrirJanela('{{ route('certificados.sendLinkForm') }}', 'Enviar link para o cliente', 'lg')">
                                    <i class="fas fa-mail-bulk"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary d-none d-md-block"
                                    onclick="Tela.abrirJanela('{{ route('certificados.create') }}', 'Novo Cliente', 'lg')">
                                    <i class="fa fa-plus"></i> Novo Certificado
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="divList">
                </div>

            </div>

        </div>
        @push('page_scripts')
            <script>
                $(function() {
                    Input.cnpj('#filter_cnpj');
                    Filtro.inicializaFormBusca("#formSearch", "#divList", true);
                });

                function atualizarTela() {
                    $("#formSearch").submit();
                }

                function executarLista(titulo) {
                    var formData = $("#fmCertificados").serialize();
                    var url = "{{ route('certificados.compartilharEmLoteForm') }}";
                    Tela.abrirJanelaPost(url, titulo, formData, 'G');
                }
            </script>
        @endpush
    @else
        <div class="card overflow-hidden">
            <!-- Help Center Header -->
            <div class="help-center-header d-flex flex-column justify-content-center align-items-center">
                <h3 class="zindex-1 text-center">Você não possui nenhum certificado cadastrado</h3>

                <p class="zindex-1 text-center px-3 mb-0 mt-3">
                    <button class="btn btn-primary btn-lg"
                        onclick="Tela.abrirJanela('{{ route('certificados.create') }}', 'Novo Certificado', 'lg')">
                        <i class="fa fa-plus" style="margin-right: 10px;"></i> Cadastrar meu primeiro certificado
                    </button>
                </p>

                <p class="zindex-1 text-center px-3 mb-0 mt-3">
                    <button class="btn btn-primary btn-lg"
                        onclick="Tela.abrirJanela('{{ route('certificados.sendLinkForm') }}', 'Novo Certificado', 'lg')">
                        <i class="fas fa-envelope-open-text"></i> Enviar link para cliente
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
    @endif
</x-app-layout>
