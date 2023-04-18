<x-app-layout>
    <h4 class="breadcrumb-wrapper">
        Treinos
    </h4>
    <div class="content">

        @include('flash::message')

        <div class="clearfix"></div>

        <form name="formSearch" id="formSearch" method="post" action="{{ route('treinos.search') }}">
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
                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2  form-group">
                            <label class="form-label">Nome do treino:</label>
                            <input type="text" class="form-control" placeholder="Ex: Joaquin"
                                name="filter_nome" id="filter_nome">
                        </div>
                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2  form-group pt-4 text-right">
                            <button type="submit" class="btn btn-sm btn-primary" id="btnSearch">
                                <i class="fa fa-search"></i> Pesquisar
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
                    <button type="button" onclick="$('#divSearch').slideToggle();" class="btn btn-sm btn-primary"
                        id="orederStatistics">
                        <i class="fa fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary"
                        onclick="Tela.abrirJanela('{{ route('treinos.create') }}', 'Novo Treino', 'xs')">
                        <i class="fa fa-plus"></i> Adicionar
                    </button>
                </div>
            </div>

            <div id="divList">

            </div>
        </div>
    </div>
    @push('page_scripts')
        <script>
            $(function() {
                Filtro.inicializaFormBusca("#formSearch", "#divList", true);
            });
        </script>
    @endpush
</x-app-layout>
