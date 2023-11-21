/**
 * Funções para campos de formulários.
 *
 * Exemplos:
 * formatar cnpj, cpf, data e etc.
 * validar cnpj, cpf, data e etc.
 *
 */

const Input = {
    /**
     * Definie um input com mascara de valor monetario
     * @param  {[type]} ID do componente html
     */
    dinheiro: function (componente, requestFocus = false) {
        $(componente).maskMoney({
            precision: 2,
            decimal: ',',
            thousands: '.',
            allowZero: true
        });
        if (requestFocus){
            $(componente).focus();
        }
    },

    /**
     * Definie um input apenas de números, sem grupo e sem decimal
     * @param  {[type]} ID do componente html
     */
    numero: function (componente) {
        $(componente).maskMoney({
            precision: 0,
            thousands: '',
            allowZero: true
        });
    },

    /**
     * Definie um input com as mascara de data
     * @param  {[type]} ID do componente html
     */
    data: function (componente) {
        $(componente).mask("99/99/9999");
    },

    cnae: function (componente) {
        $(componente).mask("99.99-9-99");
    },

    ncm: function (componente) {
        $(componente).mask("9999.99.99");
    },

    /**
     * Definie um input para exibir o calendario durante o foco
     * @param  {[type]} ID do componente html
     */
    calendario: function (componente) {
        $(componente).mask("99/99/9999");
        $(componente).datepicker({
            language: 'pt-BR'
            , format: 'dd/mm/yyyy'
            , autoclose: true
            , clearBtn: true
            , todayBtn: "linked"
            , todayHighlight: true
        });
    },

    /**
     * Definie um input para exibir o calendario com limite inicial e final durante o foco
     * @param  {[type]} ID do componente html
     */
    calendarioComLimites: function (componente, dataInicial, dataFinal) {
        $(componente).mask("99/99/9999");
        $(componente).datepicker({
            language: 'pt-BR'
            , format: 'dd/mm/yyyy'
            , startDate: dataInicial
            , endDate: dataFinal
            , autoclose: true
            , clearBtn: true
        });
    },

    /**
     * Definie um input para exibir o calendario com limite inicial e final durante o foco
     * @param  {[type]} ID do componente html
     */
    telefone: function (componente) {
        $(componente)
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            });
    },

    /**
     * Define um campo como CNPJ
     * @param  {[type]} ID do componente html
     */
    cnpj: function (componente) {
        $(componente).mask("99.999.999/9999-99");
        $(componente).blur(function () {
            if ($(this).val() == '') {
                return false;
            }

            if (!Utils.validarCnpj($(this).val())) {
                Tela.avisoComErro(
                    "CNPJ inválido. Favor verificar se digitou corretamente."
                );
                $(this).val('');
            }
        });
    },

    /**
     * Define um campo como CPF
     * @param  {[type]} ID do componente html
     */
    cpf: function (componente) {
        $(componente).mask("999.999.999-99");
        $(componente).blur(function () {
            if ($(this).val() == '') {
                return false;
            }

            if (!Utils.validarCpf($(this).val())) {
                Tela.avisoComErro(
                    "CPF inválido. Favor verificar se digitou corretamente."
                );
                $(this).val('');
            }
        });
    },

    /**
     * Define um campo como CNPJ ou CPF, podendo digitar qualquer um dos dois.
     * @param  {[type]} ID do componente html
     */
    cnpjCpf: function (componente) {
        $(componente).on('blur', function () {
            var doc = $(this).val();
            if (doc !== '') {
                var valido = Utils.validarDocumento(doc);
                $(this).val(Utils.formatarCnpjCpf(doc));
                if (!valido) {
                    Tela.avisoComErro(
                        "CPF inválido. Favor verificar se digitou corretamente."
                    );
                    $(this).val('');
                }
            }
        });
    },

    /**
     * Define um campo com mascara para numero de processo
     * @param  {[type]} ID do componente html
     */
    placa: function (componente) {
        $(componente).mask('aaa-9999');
    },

    /**
     * Define um campo com mascara para numero da pasta
     * @param  {[type]} ID do componente html
     */
    numeroPasta: function (componente) {
        $(componente).mask('9999.9999');
    }

};
export default Input;
