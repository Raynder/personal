@include('adminlte-templates::common.errors')
{!! Form::open(['route' => 'roles.store', 'id' => 'form', 'component' => 'roles', 'autocomplete' => 'off']) !!}
<div class="row">
    @include('price::roles.fields')
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
