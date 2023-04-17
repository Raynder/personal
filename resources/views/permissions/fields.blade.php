{!! Form::hidden('guard_name', 'web') !!}
<div class="form-group col-sm-12 required">
    {!! Form::label('name', 'Nome:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 150, 'required']) !!}
</div>

<div class="form-group col-sm-12 required">
    {!! Form::label('description', 'Descrição:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 2, 'cols' => 60 , 'maxlength' => 150, 'required']) !!}
</div>
