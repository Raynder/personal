@include('adminlte-templates::common.errors')
{!! Form::open(['route' => 'users.store', 'id' => 'form', 'component' => 'users', 'autocomplete' => 'off']) !!}
<div class="row">
    @include('users.fields')
    <x-form-buttons :create="true" />
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
