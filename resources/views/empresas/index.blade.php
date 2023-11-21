<x-app-layout>
    <h4 class="breadcrumb-wrapper">
        <div class="breadcrumb-pre-title">cadastros</div>
        <div class="breadcrumb-title">Empresas</div>
    </h4>
    <div class="content">

        @include('flash::message')

        <div class="card">
            <div class="card-header">
                <form name="formSearch" id="formSearch" method="post" action="{{ route('empresas.search') }}">
                    @csrf
                    {!! Form::hidden('page', 0, ['id' => 'page']) !!}
                    {!! Form::hidden('filter_sort', 'id') !!}
                    {!! Form::hidden('filter_order', 'desc') !!}
                    {!! Form::hidden('filter_take', '10', ['id' => 'filter_take']) !!}
                    <div class="d-flex justify-content-between" id="divSearch" style="display:none">
                        <div class="d-flex justify-content-aroud">
                            <div class="" style="margin-right: 15px;min-width: 300px;">
                                <input type="text" class="form-control" placeholder="00.000.000/0000-00"
                                    name="filter_cnpj">
                            </div>
                            <div class="" style="min-width: 300px;">
                                <input type="text" class="form-control" placeholder="Nome ou parte do nome da empresa"
                                    name="filter_razao_social">
                            </div>
                        </div>
        
        
                        <div class="d-flex justify-content-end gap-2" style="min-width: 400px;">
                            <button type="submit" class="btn btn-sm btn-primary" id="btnSearch">
                                <i class="fa fa-search"></i> Pesquisar
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" id="btnReset"
                                onclick="document.getElementById('formSearch').reset();">
                                <i class="fa fa-eraser"></i> Limpar
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary"
                                onclick="Tela.abrirJanela('{{ route('empresas.create') }}', 'Nova Empresa', 'lg')">
                                <i class="fa fa-plus"></i> Adicionar
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
        </script>
    @endpush
</x-app-layout>
