@include('adminlte-templates::common.errors')
{!! Form::model($role, [
    'route' => ['roles.updatePermissions', $role->id],
    'id' => 'form',
    'component' => '$role',
    'autocomplete' => 'off',
    'method' => 'post',
]) !!}
<h5>
    Grupo: {{ $role->name }}
</h5>
<div class="divider" style="margin: 0 0 5px;">
    <div class="divider-text"><b>Marque as permissões do grupo</b></div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-check">
            <input class="form-check-input permissionCheckAll" type="checkbox" id="permissionCheckAll"
                name="permission_all">
            <label class="form-check-label" for="permissionCheckAll"> Marcar todos</label>
        </div>
    </div>
</div>
@if ($permissions)
    <div class="row" style="max-height: 200px; overflow: auto; border: 1px solid #cecece">
        <div class="col-md-12">
            @foreach ($permissions as $obj)
                <div class="form-check">
                    <input class="form-check-input permissionCheck" type="checkbox" value="{{ $obj->id }}"
                        id="permission_id{{ $obj->id }}" name="permission_id[]"
                        @if ($role->hasPermissionTo($obj->name)) checked @endif>
                    <label class="form-check-label" for="permission_id{{ $obj->id }}"> {{ $obj->description }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
@endif

<x-form-buttons :create="false" />
{!! Form::close() !!}
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistro($(this));
            e.preventDefault();
        });

        $("#permissionCheckAll").on('change', function() {
            Utils.selecionarTodosOsCheckBoxes('permissionCheck', 'permissionCheckAll');
        });
    });
</script>
