<div class="row" id="showUser">
    <div class="form-group col-sm-12 required">
        {!! Form::label('name', 'Nome:') !!}
        {!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-12 required">
        {!! Form::label('email', 'E-mail:') !!}
        {!! Form::text('email', $user->email, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-12 required">
        {!! Form::label('client_token', 'Token Cliente:') !!}
        {!! Form::text('client_token', $user->empresas->first()->client_token, ['class' => 'form-control']) !!}
    </div>
    {{--<div class="form-group col-sm-6 required">
        {!! Form::label('role', 'Grupo:') !!}
        {!! Form::text('role', count($user->roles) > 0 ? $user->roles->first()->name : '', ['class' => 'form-control']) !!}
    </div>--}}
</div>
@if ($user->empresas)
    <div class="divider">
        <div class="divider-text"><b>Empresas liberadas para este usuáro</b></div>
    </div>
    <div class="table-responsive " style="margin: 0; height: 200px;">
        <table class="table table-hover">
            <thead class="table-header">
                <tr class="sticky-top">
                    <th>CNPJ</th>
                    <th>Empresa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->empresas as $obj)
                    <tr>
                        <td>{{ App\Helpers\FormatterHelper::formatCnpjCpf($obj->cnpj) }}</td>
                        <td>{{ $obj->razao_social }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Nenhuma empresa liberada</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif
<script>
    $(function() {
        $('#showUser input').attr('disabled', 'true');
    })
</script>
