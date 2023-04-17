{!! Form::hidden('guard_name', 'web') !!}

<div class="form-group col-sm-12 required">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 150, 'required']) !!}
</div>
