@include('adminlte-templates::common.errors')
{!! Form::model($user, [
    'route' => ['users.updatePasswordAdmin', $user->id],
    'id' => 'form',
    'component' => '$user',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row mt-3">
    <div class="form-group col-sm-12 required">
        {!! Form::label('password', 'Nova Senha:') !!}
        {!! Form::password('password', [
            'class' => 'form-control',
            'required',
            'placeholder' => 'Digite a nova senha desejada',
        ]) !!}
    </div>
</div>
<div class="row mt-3">
    <div class="form-group col-sm-12 required">
        {!! Form::label('password_confirmation', 'Confirme a Nova Senha:') !!}
        {!! Form::password('password_confirmation', [
            'class' => 'form-control',
            'required',
            'placeholder' => 'Confirme aqui a nova senha digitada',
        ]) !!}
    </div>
    <x-form-buttons :create="false" />
</div>
{!! Form::close() !!}
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistro($(this));
            e.preventDefault();
        });
    });
</script>
