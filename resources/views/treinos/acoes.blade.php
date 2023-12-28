{!! Form::open([
    'route' => 'acessos.acoes',
    'id' => 'form',
    'component' => 'acessos',
    'autocomplete' => 'off',
]) !!}
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <div class="form-group col-sm-12 ">
                    <span class="d-block  fw-semibold">
                        Escolha o que fazer.
                    </span>
                </div>
                <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input chkAcao" type="radio" name="status" id="desinstalar" value="PD"
                        required="" checked>
                    <label class="form-check-label" for="desinstalar">Desinstalar</label>
                </div>
                <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input chkAcao" type="radio" name="status" id="reinstalar" value="PA"
                        required="">
                    <label class="form-check-label" for="reinstalar">Reinstalar</label>
                </div>
                <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input chkAcao" type="radio" name="status" id="excluir" value="E"
                        required="">
                    <label class="form-check-label" for="excluir">Excluir</label>
                </div>
                <input type="hidden" name="id" value="{{$acesso->id}}">
            </div>
        </div>
    </div>
    <div class="form-group col-sm-12 d-flex justify-content-end mt-4">
        <div>
            <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">
                <i class="fa fa-ban"></i> Cancelar
            </button>
            <button type="submit" form="form" class="btn btn-sm btn-primary">
                <i class="fa fa-check"></i> Confirmar
            </button>
        </div>
    </div>
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
