<x-app-layout>
    <h4 class="breadcrumb-wrapper">
        <div class="breadcrumb-pre-title">Utilitários</div>
        <div class="breadcrumb-title">Logs</div>
    </h4>
    <div class="content">

        @include('flash::message')

        <div class="clearfix"></div>

        <form name="formSearch" id="formSearch" method="post" action="{{ route('acessos.search') }}">
            @csrf
            {!! Form::hidden('page', 0, ['id' => 'page']) !!}
            <div class="card mb-1" id="divSearch" style="display:none">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fa fa-search"></i> Formulário de pesquisa</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2 form-group">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" placeholder="Ex: João   "
                                name="filter_usuario">
                        </div>

                        <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2 form-group">
                            <label class="form-label">Empresa</label>
                            <input type="text" class="form-control" placeholder="Ex: João   "
                                name="filter_empresa">
                        </div>

                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 form-group">
                            <label for="filter_sort" class="form-label">Ordem</label>
                            <select name="filter_sort" id="filter_sort" class="form-control" style="width: 100%">
                                <option value="id">-</option>
                            </select>
                        </div>

                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 form-group">
                            <label for="filter_status" class="form-label">Status</label>
                            <select name="filter_status" id="filter_status" class="form-control" style="width: 100%">
                                <option value="">-</option>
                                @foreach (App\Helpers\StatusAcessoHelper::getAllStatus() as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 form-group">
                            <label for="filter_order" class="form-label">Direção</label>
                            <select name="filter_order" id="filter_order" class="form-control" style="width: 100%">
                                <option value="asc">A-Z</option>
                                <option value="desc" selected>Z-A</option>
                            </select>
                        </div>

                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" title="Quantidade de logs por página">
                            <div class="form-group">
                                <label for="filter_take" class="form-label">#</label>
                                <select name="filter_take" id="filter_take" class="form-control" style="width: 100%">
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="1000">1000</option>
                                </select>
                            </div>
                        </div>
                        {{--<div class="col-xs-3 col-sm-1 col-md-1 col-lg-1 form-group" title="Exibir logs excluídos">
                            <label for="filter_deleted" class="form-label"><i class="fa fa-trash"></i></label>
                            <select name="filter_deleted" id="filter_deleted" class="form-control" style="width: 100%">
                                <option value="N" selected>NAO</option>
                                <option value="S">SIM</option>
                            </select>
                        </div>--}}
                    </div>
                </div>

                <div class="card-footer clearfix">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary" id="btnSearch">
                            <i class="fa fa-search"></i> Pesquisar
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" id="btnReset"
                            onclick="document.getElementById('formSearch').reset();">
                            <i class="fa fa-eraser"></i> Limpar
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" id="btnClose"
                            onclick="$('#divSearch').slideToggle();">
                            <i class="fa fa-door-open"></i> Fechar
                        </button>
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
