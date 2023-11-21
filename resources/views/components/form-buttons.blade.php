@props(['create' => true])
<div class="form-group col-sm-12 d-flex justify-content-between mt-4" id="footerModal">
    <div>
        @if ($create)
            <div class="form-check bx-pull-left">
                <input class="form-check-input" type="checkbox" value="" id="continue" name="continue">
                <label class="form-check-label" for="continue"> Continuar </label>
            </div>
        @endif
    </div>
    <div>
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="fa fa-check"></i> {{ $create ? 'Salvar' : 'Atualizar' }}
        </button>
        <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">
            <i class="fa fa-ban"></i> Cancelar
        </button>
    </div>
</div>
