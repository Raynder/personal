<x-app-layout>
    @if (count($alunos) > 0)
        <h4 class="breadcrumb-wrapper">
            Alunos
        </h4>
        <div class="content">

            @include('flash::message')

            <div class="clearfix"></div>

            <form name="formSearch" id="formSearch" method="post" action="{{ route('alunos.search') }}">
                @csrf
                {!! Form::hidden('page', 0, ['id' => 'page']) !!}
                {!! Form::hidden('filter_sort', 'id') !!}
                {!! Form::hidden('filter_order', 'desc') !!}
                {!! Form::hidden('filter_take', '10') !!}
                <div class="card mb-1" id="divSearch">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fa fa-search"></i> Formulário de pesquisa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 form-group">
                                <label class="form-label">Razão Social / Fantasia</label>
                                <input type="text" class="form-control"
                                    placeholder="Nome ou parte do nome da aluno" name="filter_nome">
                            </div> --}}
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2  form-group">
                                <label class="form-label">CNPJ</label>
                                <input type="text" class="form-control" placeholder="00.000.000/0000-00"
                                    name="filter_cnpj" id="filter_cnpj">
                            </div>
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2  form-group pt-4 text-right">
                                <button type="submit" class="btn btn-sm btn-primary" id="btnSearch">
                                    <i class="fa fa-search"></i> Pesquisar
                                </button>
                            </div>
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2  form-group pt-4">
                                <button type="button" class="btn btn-sm btn-secondary"
                                    onclick="Tela.abrirJanela('{{ route('alunos.create') }}', 'Novo Cliente', 'lg')">
                                    <i class="fa fa-plus"></i> Adicionar
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Listagem</h5>
                    <div class="button">
                    </div>
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
            </script>
        @endpush
    @else
        <div class="card overflow-hidden">
            <!-- Help Center Header -->
            <div class="help-center-header d-flex flex-column justify-content-center align-items-center">
                <h3 class="zindex-1 text-center">Você não possui nenhum aluno cadastrado</h3>

                <p class="zindex-1 text-center px-3 mb-0 mt-3">
                    <button class="btn btn-primary btn-lg"
                        onclick="Tela.abrirJanela('{{ route('alunos.create') }}', 'Novo Aluno', 'xs')">
                        <i class="fa fa-plus" style="margin-right: 10px;"></i> Cadastrar meu primeiro aluno
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
