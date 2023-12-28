<script>
    var alunosCount = {{ $treino->alunos->count() }} + 0;

    function addAluno() {
        var clone = $("#aluno_clone").clone();
        var htmlId = 'alunos[' + alunosCount + ']';
        var btnExcluir = clone.find("button.btn_excluir_aluno_clone");
        // pegar todos os campos da div clone
        var idInput = clone.find("input[name$='alunos[_clone][id]']");
        var deletedInput = clone.find("input[name$='alunos[_clone][deleted]']");
        var newInput = clone.find("input[name$='alunos[_clone][new]']");
        var alunoInput = clone.find("select[name$='alunos[_clone][aluno_id]']");

        idInput.attr('id', htmlId + '[id]').attr('name', htmlId + '[id]');
        deletedInput.attr('id', htmlId + '[deleted]').attr('name', htmlId + '[deleted]');
        newInput.attr('id', htmlId + '[new]').attr('name', htmlId + '[new]');
        alunoInput.attr('id', htmlId + '[aluno_id]').attr('name', htmlId + '[aluno_id]');

        clone.find("tr").attr('id', 'aluno' + alunosCount);
        clone.find("tr").attr('data-idx', alunosCount);
        btnExcluir.bind('click', excluirCertificado);

        $("#alunoList").append(clone.find("tr"));
        clone.show();
        Filtro.inicializaCampoBusca(
            '{{ route('alunos.find') }}',
            alunoInput,
            "Aluno",
            "#modalBasic"
        );
        $(alunoInput).focus();
        alunosCount++;
    }

    function excluirCertificado() {
        var prnt = $(this).parents(".linha-aluno");
        prnt.remove();
    }
</script>

<div class="row" style="padding-left: 20px;">
    <div class="form-group col-sm-4 pb-3">
        {!! Form::label('nome', 'Nome do Treino:') !!}
        {!! Form::text('nome', null, ['class' => 'form-control', 'id' => 'nome', 'maxlength' => 20]) !!}
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
    <div class="nav-align-top">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                    data-bs-target="#navs-top-usuarios" aria-controls="navs-top-usuarios" aria-selected="true">
                    Exercicios
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                    data-bs-target="#navs-top-alunos" aria-controls="navs-top-alunos" aria-selected="false">
                    Alunos
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="navs-top-usuarios" role="tabpanel">
                <div style="max-height: 400px; overflow-y: auto; overflow-x: hidden">
                    <table class="table table-hover">
                        <thead class="table-header">
                            <tr class="sticky-top">
                                <th style="width: 10px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="usuario_id_todos"
                                            name="usuario_id_todos"
                                            onclick="Utils.selecionarTodosOsCheckBoxes('chkUsuarioId', 'usuario_id_todos')" />
                                    </div>
                                </th>
                                <th>Nome</th>
                                <th>Apelido</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $obj)
                                @if ($treino->usuarios->contains(fn($item) => $item->id == $obj->id))
                                    <tr id="row_{{ $obj->id }}">
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" name="usuario_id[]"
                                                    id="usuario_id_{{ $obj->id }}"
                                                    class="form-check-input chkUsuarioId" value="{{ $obj->id }}"
                                                    checked />
                                            </div>
                                        </td>
                                        <td><label for="usuario_id_{{ $obj->id }}">{{ $obj->nome }}</label></td>
                                        <td>{{ $obj->apelido }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td colspan="3" style="border-bottom: 5px solid silver; padding: 0; height: 1px;">
                                </td>
                            </tr>
                            @forelse($usuarios as $obj)
                                @if (!$treino->usuarios->contains(fn($item) => $item->id == $obj->id))
                                    <tr id="row_{{ $obj->id }}">
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" name="usuario_id[]"
                                                    id="usuario_id_{{ $obj->id }}"
                                                    class="form-check-input chkUsuarioId"
                                                    value="{{ $obj->id }}" />
                                            </div>
                                        </td>
                                        <td><label for="usuario_id_{{ $obj->id }}">{{ $obj->nome }}</label>
                                        </td>
                                        <td>{{ $obj->apelido }}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td>Nenhum exercicio registrado ainda</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="navs-top-alunos" role="tabpanel">
                <div style="max-height: 400px; overflow-y: auto; overflow-x: hidden">
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Alunos</th>
                                <th style="width: 16px"></th>
                            </tr>
                        </thead>
                        <tbody id="alunoList">
                            @include('treinos.alunos', [
                                'treino' => $treino,
                            ])
                        </tbody>
                    </table>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-xs btn-primary" onclick="addAluno();">
                            Vincular Alunos
                        </button>
                    </div>
                </div>


                <div class="col-md-3" data-bs-toggle="tooltip" data-color="secondary" data-bs-placement="top"
                    data-bs-original-title="Andamento do relacionamento com alunos">
                    <div id="chartItensCertificados" style="min-height:230px;">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
