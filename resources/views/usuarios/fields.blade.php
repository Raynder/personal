<script>
    var gruposCount = {{ $usuario->grupos->count() }} + 0;

    function addGrupo() {
        var clone = $("#grupo_clone").clone();
        var htmlId = 'grupos[' + gruposCount + ']';
        var btnExcluir = clone.find("button.btn_excluir_grupo_clone");
        // pegar todos os campos da div clone
        var idInput = clone.find("input[name$='grupos[_clone][id]']");
        var deletedInput = clone.find("input[name$='grupos[_clone][deleted]']");
        var newInput = clone.find("input[name$='grupos[_clone][new]']");
        var grupoInput = clone.find("select[name$='grupos[_clone][grupo_id]']");

        idInput.attr('id', htmlId + '[id]').attr('name', htmlId + '[id]');
        deletedInput.attr('id', htmlId + '[deleted]').attr('name', htmlId + '[deleted]');
        newInput.attr('id', htmlId + '[new]').attr('name', htmlId + '[new]');
        grupoInput.attr('id', htmlId + '[grupo_id]').attr('name', htmlId + '[grupo_id]');

        clone.find("tr").attr('id', 'grupo' + gruposCount);
        clone.find("tr").attr('data-idx', gruposCount);
        btnExcluir.bind('click', excluirGrupo);

        $("#grupoList").append(clone.find("tr"));
        clone.show();
        Filtro.inicializaCampoBusca(
            '{{ route('grupos.find') }}',
            grupoInput,
            "Grupo",
            "#modalBasic"
        );
        $(grupoInput).focus();
        gruposCount++;
    }

    function excluirGrupo() {
        var prnt = $(this).parents(".linha-grupo");
        prnt.remove();
    }
</script>

<div class="row" style="padding-left: 20px;">
    <div class="form-group col-sm-6 pb-3">
        <label for="apelido">Apelido:</label>
        <input type="text" value="{{ $usuario->apelido }}" class="form-control" name="apelido">
    </div>
    <div class="form-group col-sm-6 pb-3">
        <label>Nome:</label>
        <input type="text" value="{{ $usuario->nome }}" class="form-control" disabled>
    </div>

    <style>
        .cabeca {
            margin-right: 0.125rem;
            width: 100%;
            color: var(--bs-primary);
            font-weight: 700;
            font-size: 16px;
            background-color: var(--bs-info);
            background-clip: padding-box;
            margin-bottom: -1px;
            border: none;
            padding: 0.469rem 1.375rem;
        }
    </style>
    <button type="button" class="cabeca" role="tab" data-bs-toggle="tab"
        data-bs-target="#navs-pills-justified-grupos" aria-controls="navs-pills-justified-grupos" aria-selected="true">
        <i class="tf-icons bx bx-box"></i> Grupos do Usuário
    </button>
    <div class="tab-content">
        <div class="tab-pane fade active show" id="navs-pills-justified-grupos" role="tabpanel">
            <div style="max-height: 400px; overflow-y: auto; overflow-x: hidden">
                <table class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Grupos</th>
                            <th style="width: 16px"></th>
                        </tr>
                    </thead>
                    <tbody id="grupoList">
                        @include('usuarios.grupos', [
                            'usuario' => $usuario,
                        ])
                    </tbody>
                </table>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-md-12">
                    <button type="button" class="btn btn-xs btn-primary" onclick="addGrupo();">
                        Vincular Grupos
                    </button>
                </div>
            </div>


            <div class="col-md-3" data-bs-toggle="tooltip" data-color="secondary" data-bs-placement="top"
                data-bs-original-title="Andamento da classificação de usuarios">
                <div id="chartItensClassificados" style="min-height:230px;">
                </div>
            </div>
        </div>
    </div>
</div>
