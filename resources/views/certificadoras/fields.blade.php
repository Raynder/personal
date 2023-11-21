<script>
    $(function() {
        Input.telefone('#telefone');
    });
</script>
<div class="row">
    <div class="form-group col-sm-12">
        {!! Form::label('nome', 'Nome:') !!}
        {!! Form::text('nome', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12">
        {!! Form::label('site', 'Site:') !!}
        {!! Form::text('site', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-4">
        {!! Form::label('telefone', 'Telefone:') !!}
        {!! Form::text('telefone', null, ['class' => 'form-control', 'id' => 'telefone', 'maxlength' => 16]) !!}
    </div>
</div>
