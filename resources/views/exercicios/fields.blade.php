<div class="row">
    <!-- nome, descricao, img, video, musculo, tipo -->
    <div class="form-group col-sm-6">
        {!! Form::label('nome', 'Nome:') !!}
        {!! Form::text('nome', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>
     
    <div class="form-group col-sm-6">
        {!! Form::label('descricao', 'Descrição:') !!}
        {!! Form::text('descricao', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('img', 'Imagem:') !!}
        {!! Form::text('img', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('video', 'Video:') !!}
        {!! Form::text('video', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('musculo', 'Musculo:') !!}
        {!! Form::text('musculo', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::label('tipo', 'Tipo:') !!}
        {!! Form::text('tipo', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>
</div>

<div class="row">
    
</div>