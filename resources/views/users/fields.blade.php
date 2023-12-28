{{--<div class="form-group col-md-12 required">
    {!! Form::label('role_id', 'Treino de permissões:') !!}
    <select style="width:100%;" class="form-control" id="role_id" required name="role_id">
        <option value="">Informe o treino de permissões</option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ $isRoleSelected($role) }}>{{ $role->name }}
            </option>
        @endforeach
    </select>
</div>--}}

@if (!session()->has('empresa_id'))
    <div class="form-group col-sm-12 mt-3 required" id="divEmpresa">
        {!! Form::label('empresa_id', 'Empresa principal do usuário:') !!}
        <select class="form-control" id="empresa_id" name="empresa_id" required>
            @if ($user !== null && $user->empresa !== null)
                <option value="{{ $user->empresa_id }}">{{ $user->empresa->razao_social }}
                </option>
            @endif
        </select>
    </div>
@endif
<div class="form-group col-sm-12 required">
    {!! Form::label('name', 'Nome:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>
<div class="form-group col-sm-12 required">
    {!! Form::label('name', 'Nome:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>
<div class="form-group @if (!$user->id) col-sm-8 @else col-sm-12 @endif  required">
    {!! Form::label('email', 'E-mail:') !!}
    {!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
</div>
@if (!$user->id)
    <div class="form-group col-sm-4 required">
        {!! Form::label('password', 'Senha:') !!}
        {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
    </div>
@endif
<div class="divider mt-3 divEmpresas">
    <div class="divider-text"><b>Marque as empresas do usuário</b></div>
</div>
<div class="table-responsive divEmpresas" style="margin: 0;padding: 0; height: 200px;" id="divEmpresas">
    <table class="table table-sm table-hover">
        <thead class="table-header">
            <tr>
                <th style="width: 10px;">
                    <div class="form-check form-check-inline" style="margin-right: 0;">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="marcarTodos" id="marcarTodos"
                                onclick="Utils.selecionarTodosOsCheckBoxes('chkEmpresa', 'marcarTodos')" />
                        </label>
                    </div>
                </th>
                <th>CNPJ</th>
                <th>Nome</th>
                <th>UF</th>
            </tr>
        </thead>
        <tbody>
            @forelse($empresas as $empresa)
                <tr>
                    <td>
                        <input class="form-check-input chkEmpresa" type="checkbox" name="empresas[]"
                            id="chkEmpresa_{{ $empresa->id }}" value="{{ $empresa->id }}"
                            {{ $isEmpresaSelected($empresa) }} />
                    </td>
                    <td>
                        <label class="form-check-label" for="chkEmpresa_{{ $empresa->id }}">
                            {{ App\Helpers\FormatterHelper::formatCnpjCpf($empresa->cnpj) }}
                        </label>
                    </td>
                    <td>
                        {{ $empresa->razao_social }}<br />
                        {{ $empresa->fantasia }}
                    </td>
                    <td>{{ $empresa->estado }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Selecione a empresa principal.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script>
    $(function() {
        @if (!session()->has('empresa_id'))
            $("#empresa_id").on('select2:select', function() {
                $(".spinner").show();
                $.ajax({
                    url: "{{ route('empresas.getByParent') }}?empresa_id=" + $(this).val(),
                    encoding: "UTF-8",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $("#divEmpresas").html(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Ajax.tratarErroAjax(jqXHR.status, jqXHR.responseText);
                    },
                    complete: function() {
                        $(".spinner").hide();
                    }
                });
            });

            Filtro.inicializaCampoBusca(
                '{{ route('empresas.find') }}?principal=true',
                $("#empresa_id"),
                "Pesquise por nome ou cnpj",
                "#modalBasic"
            );

            $("#role_id").change(function() {
                $("#divEmpresa").show();
                $(".divEmpresas").show();
                if ($(this).val() == 1) {
                    $("#divEmpresa").hide();
                    $(".divEmpresas").hide();
                    $("#empresa_id").removeAttr('required');
                }
            })
        @endif
    });
</script>
