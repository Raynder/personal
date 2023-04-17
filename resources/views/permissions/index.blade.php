<x-app-layout>
    <h4 class="breadcrumb-wrapper">
        <div class="breadcrumb-pre-title">Utilitários</div>
        <div class="breadcrumb-title">Permissões do sistema</div>
    </h4>
    <div class="content">
        @include('flash::message')
        <div class="card">
            <div class="card-header ">
                <form name="formSearch" id="formSearch" method="post" action="{{ route('permissions.search') }}">
                    @csrf
                    {!! Form::hidden('page', 0, ['id' => 'page']) !!}
                    {!! Form::hidden('filter_take', 20, ['id' => 'filter_take']) !!}
                    <div class="d-flex justify-content-between">
                        <div class="" style="min-width: 300px;">
                            <input type="text" class="form-control" placeholder="Nome da permissão ou rotina"
                                name="filter_nome">
                        </div>
                        <div class="d-flex justify-content-end gap-2" style="min-width: 400px;">
                            <button type="button" class="btn btn-sm btn-info btn-more-filter" id="orederStatistics"
                                onclick="$('#divSearch').slideToggle();" style="">
                                <i class="fa fa-cog"></i>
                            </button>
                            <button type="submit" class="btn btn-sm btn-secondary" id="orederStatistics">
                                <i class="fa fa-search"></i> Pesquisar
                            </button>
                            @can('utilitarios.permissoes.cadastrar')
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="Tela.abrirJanela('{{ route('permissions.create') }}', 'Nova Permissão', 'lg')">
                                    <i class="fa fa-plus"></i> Adicionar
                                </button>
                            @endcan
                        </div>
                    </div>

                    <div class="" id="divSearch" style="display:none">
                        <div class="row mt-3">
                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 form-group">
                                <label for="filter_sort" class="form-label">Ordem</label>
                                <select name="filter_sort" id="filter_sort" class="form-control" style="width: 100%">
                                    <option value="id">ID</option>
                                    <option value="order" selected>Ordem</option>
                                    <option value="name">Nome</option>
                                    <option value="description">Descrição</option>
                                    <option value="created_at">Cadastro</option>
                                    <option value="updated_at">Atualização</option>
                                </select>
                            </div>

                            <div class="col-xs-3 col-sm-1 col-md-1 col-lg-1 form-group">
                                <label for="filter_order" class="form-label">Direção</label>
                                <select name="filter_order" id="filter_order" class="form-control" style="width: 100%">
                                    <option value="asc" selected>A-Z</option>
                                    <option value="desc">Z-A</option>
                                </select>
                            </div>


                            <div class="col-xs-3 col-sm-1 col-md-1 col-lg-1 form-group"
                                title="Exibir registros excluídos">
                                <label for="filter_deleted" class="form-label"><i class="fa fa-trash"></i></label>
                                <select name="filter_deleted" id="filter_deleted" class="form-control"
                                    style="width: 100%">
                                    <option value="N" selected>NAO</option>
                                    <option value="S">SIM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div id="divList"></div>
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
