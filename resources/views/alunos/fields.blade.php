<div class="row">
    <div class="form-group col-sm-6">
        {!! Form::label('nome', 'Aluno:') !!}
        {!! Form::text('nome', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('email', 'Email:') !!}
        {!! Form::email('email', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>
</div>

<div class="row">
    
</div>