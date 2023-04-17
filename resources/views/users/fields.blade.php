<div class="form-group col-sm-12 required">
    {!! Form::label('name', 'Nome:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>
<div class="form-group @if ($user->id == null) col-sm-8 @else col-sm-12 @endif  required">
    {!! Form::label('email', 'E-mail:') !!}
    {!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
</div>
@if ($user->id == null)
    <div class="form-group col-sm-4 required">
        {!! Form::label('password', 'Senha:') !!}
        {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
    </div>
@endif
<div class="form-group col-md-12 required">
    {!! Form::label('role_id', 'Grupo de permissões:') !!}
    <select style="width:100%;" class="form-control" id="role_id" required name="role_id">
        <option value="">Informe o grupo de permissões</option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ $isRoleSelected($role) }}>{{ $role->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="divider mt-3">
    <div class="divider-text"><b>Marque as empresas do usuário</b></div>
</div>
<div class="table-responsive" style="margin: 0;padding: 0; height: 200px;">
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
            </tr>
        </thead>
        <tbody>
            @forelse($empresas as $empresa)
                <tr>
                    <td>
                        <input class="form-check-input chkEmpresa" type="checkbox" name="empresas"
                            id="chkEmpresa_{{ $empresa->id }}" value="{{ $empresa->id }}"
                            {{ $isEmpresaSelected($empresa) }} />
                    </td>
                    <td>
                        <label class="form-check-label" for="chkEmpresa_{{ $empresa->id }}">
                            {{ App\Helpers\FormatterHelper::formatCnpjCpf($empresa->cnpj) }}
                        </label>
                    </td>
                    <td>{{ $empresa->razao_social }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Nenhum empresa disponível</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
