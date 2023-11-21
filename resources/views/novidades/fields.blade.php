<script>
    $(function() {
        $(".select2-padrao").select2({
            dropdownParent: "#modalBasic"
        });
    });
</script>

<div class="row">
    <div class="form-group col-sm-8">
        {!! Form::label('title', 'Título:') !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('type', 'Tipo:') !!}
        {!! Form::select('type', $tipos, null, ['class' => 'form-control select2-padrao']) !!}
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-12">
        {!! Form::label('description', 'Descrição:') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4">
        {!! Form::label('version', 'Versão:') !!}
        {!! Form::text('version', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

    <div class="form-group col-sm-8">
        {!! Form::label('link', 'Link:') !!}
        {!! Form::text('link', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>
</div>
