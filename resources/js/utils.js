/**
 * Funcoes utilitarias para usar em todo o sistema.
 *
 * Exemplos:
 * formatar cnpj, cpf, data e etc.
 * validar cnpj, cpf, data e etc.
 *
 */

import { upperCase } from "lodash";

 const Utils = {
        /**
         * Selecionar ou desmarcar todos os checkboxes passados no parametro.
         * @param nomeCheckBoxLista        : O nome da classe de todos os checkboxes que devem ser marcados/desmarcados ao clicar em
         *      componenteSelecionavel
         *                                 : Deve ser passado o nome da classe CSS para buscar os componentes.
         * @param componenteSelecionavel   : Deve ser passado o ID único do componente que fara a marcacao dos checkboxes.
         * @type {{selecionarTodosOsCheckBoxes}}
         */
        selecionarTodosOsCheckBoxes: function (nomecheckBoxLista, componenteSelecionavel) {
            let boxes = document.getElementsByClassName(nomecheckBoxLista);
            let i = 0;
            for (i; i < boxes.length; i++) {
                boxes[i].checked = document.getElementById(componenteSelecionavel).checked;
            }
        },

        /**
         * Funcao para validar o cnpj passado no parametro.
         * Retorna true para valido e false para invalido.
         * @param valor
         * @returns {boolean}
         */
        validarCnpj: function (valor) {
            let cnpj = this.limpaCampoNumerico(valor);
            let nonNumbers = /\D/;
            if (nonNumbers.test(cnpj)) {
                return false;
            }
            let invalidos = [
                '11111111111111',
                '22222222222222',
                '33333333333333',
                '44444444444444',
                '55555555555555',
                '66666666666666',
                '77777777777777',
                '88888888888888',
                '99999999999999'
            ];
            if (invalidos.indexOf(cnpj) != -1) {
                return false;
            }
            let a = [];
            let b = new Number;
            let c = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            let i = 0;
            let x = 0;
            let y = 0;
            for (i; i < 12; i++) {
                a[i] = cnpj.charAt(i);
                b += a[i] * c[i + 1];
            }
            if ((x = b % 11) < 2) {
                a[12] = 0
            } else {
                a[12] = 11 - x
            }
            b = 0;
            for (y = 0; y < 13; y++) {
                b += (a[y] * c[y]);
            }
            if ((x = b % 11) < 2) {
                a[13] = 0;
            } else {
                a[13] = 11 - x;
            }
            if ((cnpj.charAt(12) != a[12]) || (cnpj.charAt(13) != a[13])) {
                return false;
            }
            return true;
        },
        /**
         * Funcao para validar o CPF passado no parametro.
         * Retorna true para valido e false para invalido.
         * @param cpf
         * @returns {boolean}
         */
        validarCpf: function (cpf) {
            let i;
            let s = this.limpaCampoNumerico(cpf);
            let invalidos = [
                '11111111111',
                '22222222222',
                '33333333333',
                '44444444444',
                '55555555555',
                '66666666666',
                '77777777777',
                '88888888888',
                '99999999999'
            ];
            if (invalidos.indexOf(s) != -1) {
                return false;
            }
            let c = s.substr(0, 9);
            let dv = s.substr(9, 2);
            let d1 = 0;
            for (i = 0; i < 9; i++) {
                d1 += c.charAt(i) * (10 - i);
            }
            if (d1 == 0) {
                return false;
            }
            d1 = 11 - (d1 % 11);
            if (d1 > 9)
                d1 = 0;
            if (dv.charAt(0) != d1) {
                return false;
            }
            d1 *= 2;
            for (i = 0; i < 9; i++) {
                d1 += c.charAt(i) * (11 - i);
            }
            d1 = 11 - (d1 % 11);
            if (d1 > 9)
                d1 = 0;
            if (dv.charAt(1) != d1) {
                return false;
            }
            return true;
        },

        /**
         * Funcao para validar tanto CNPJ quando CPF.
         * Sera verificado o tamanho do valor passado para validar de forma correta.
         *
         * @param documento
         * @returns boolean
         */
        validarDocumento: function (documento) {
            let doc = this.limpaCampoNumerico(documento);
            if (doc.length === 11) {
                return this.validarCpf(doc);
            } else if (doc.length === 14) {
                return this.validarCnpj(doc);
            }
            return false;
        },

        /**
         * Funcao para formatar o campo CEP.
         * Retorna uma string vazia em caso de tamanho insuficiente.
         * @param cep
         * @returns {string|*}
         */
        formatarCep: function (cepStr) {
            let cep = this.limpaCampoNumerico(cepStr);
            if (cep.length == 8) {
                let aux = cep.substring(0, 2) + ".";
                aux = aux + cep.substring(2, 5) + "-";
                aux = aux + cep.substring(5, 8);
                return aux;
            }
            return "";
        },

        /**
         * Funcao para formatar o telefone passado, podendo ser com 10 ou 11 caracteres.
         * Se o valor passado for diferente, sera retornado uma string vazia para limpar o campo.
         * @param fone
         * @returns {*}
         */
        formatarTelefone: function (fone) {
            if (fone.length > 0) {
                fone = this.limpaCampoNumerico(fone);
                if (fone.length == 10) {
                    let aux = "(" + fone.substring(0, 2) + ") ";
                    aux = aux + fone.substring(2, 6) + "-";
                    aux = aux + fone.substring(6, 10);
                    return aux;
                } else if (fone.length == 11) {
                    if ((fone.substring(0, 4) === '0800' ) || (fone.substring(0, 4) === '0300') || (fone.substring(0, 4) === '0500')) {
                        let aux = fone.substring(0, 4);
                        aux = aux + " " + fone.substring(4, 7);
                        aux = aux + " " + fone.substring(7, 11);
                        return aux;
                    } else {
                        let aux = "(" + fone.substring(0, 2) + ") ";
                        aux = aux + fone.substring(2, 7) + "-";
                        aux = aux + fone.substring(7, 11);
                        return aux;
                    }
                }
            }
            return "";
        },

        /**
         * Retira todos os caracteres que nao sao numeros.
         * @param numero
         */
        limpaCampoNumerico: function (numero) {
            let Digitos = "0123456789";
            let temp = "";
            let digito = "";
            for (let i = 0; i < numero.length; i++) {
                digito = numero.charAt(i);
                if (Digitos.indexOf(digito) >= 0) {
                    temp = temp + digito;
                }
            }
            return temp
        },

        /**
         * @param documento
         * @returns cnpj ou cpf formatado ou string vazia em caso de valor com quantidade de digitos insuficiente.
         */
        formatarCnpjCpf: function (documento) {
            let doc = this.limpaCampoNumerico(documento);
            let aux;
            if (doc.length == 11) {
                aux = doc.substring(0, 3);
                aux = aux + "." + doc.substring(3, 6);
                aux = aux + "." + doc.substring(6, 9);
                aux = aux + "-" + doc.substring(9, 11);
                return aux;
            } else if (doc.length == 14) {
                aux = doc.substring(0, 2);
                aux = aux + "." + doc.substring(2, 5);
                aux = aux + "." + doc.substring(5, 8);
                aux = aux + "/" + doc.substring(8, 12);
                aux = aux + "-" + doc.substring(12, 14);
                return aux;
            }
            return "";
        },

        validarData: function (campo) {
            let expReg = /^(([0-2]\d|[3][0-1])\/([0]\d|[1][0-2])\/[1-2][0-9]\d{2})$/;
            let msgErro = 'Formato invalido de data.';
            if ((campo.value.match(expReg)) && (campo.value != '')) {
                let dia = campo.value.substring(0, 2);
                let mes = campo.value.substring(3, 5);
                let ano = campo.value.substring(6, 10);
                if (mes == 4 || mes == 6 || mes == 9 || mes == 11 && dia > 30) {
                    alert("Dia incorreto !!! O mes especificado contem no maximo 30 dias.");
                    return false;
                } else {
                    if (ano % 4 != 0 && mes == 2 && dia > 28) {
                        alert("Data incorreta!! O mes especificado contem no maximo 28 dias.");
                        return false;
                    } else {
                        if (ano % 4 == 0 && mes == 2 && dia > 29) {
                            alert("Data incorreta!! O mes especificado contem no maximo 29 dias.");
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                alert(msgErro);
                campo.focus();
                return false;

            }

        },

        /**
         * Carrega os dados do cep do site para pre-carregar nos cadastros
         * @param cep
         */
        carregarCep: async function (cep) {
            let url = "https://viacep.com.br/ws/" + cep +"/json/";

            let resultadoCEP = await(
                fetch(url).then((resultadoCEP) => resultadoCEP.json())
            );

            console.log(resultadoCEP)

            $("#logradouro").val(unescape(resultadoCEP["logradouro"]));
            $("#bairro").val(unescape(resultadoCEP["bairro"]));
            $("#cidade").val(unescape(resultadoCEP["localidade"]));

            let uf = upperCase(unescape(resultadoCEP['uf']))

            $("#estado").val(uf);
            $('#estado').trigger('change')

        },

        formatarData: function (dt) {
            if(dt != null){
                let data = moment(dt, "YYYY-MM-DD");
                let dia = data.date();
                if (dia.toString().length == 1)
                    dia = "0" + dia;
                let mes = data.month() + 1;
                if (mes.toString().length == 1)
                    mes = "0" + mes;
                let ano = data.year();
                return dia + "/" + mes + "/" + ano;
            } else{
                return '';
            }
        },

        floatParaMoeda: function (num) {
            let x = 0;
            if (num < 0) {
                num = Math.abs(num);
                x = 1;
            }
            if (isNaN(num)) num = "0";
            let cents = Math.floor((num * 100 + 0.5) % 100);
            num = Math.floor((num * 100 + 0.5) / 100).toString();
            if (cents < 10) cents = "0" + cents;
            for (let i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
                num = num.substring(0, num.length - (4 * i + 3)) + '.'
            + num.substring(num.length - (4 * i + 3));
            let ret = num + ',' + cents;
            if (x == 1) ret = ' - ' + ret;
            return ret;
        },

        calcularValorLiquido: function (original, multa, juros, desconto, endereco, token, campo, pagrec) {
            $.ajax({
                url : endereco,
                type: 'POST',
                data: {
                    original    : original,
                    multa       : multa,
                    juros       : juros,
                    desconto    : desconto,
                    _token      : token,
                    pagrec      : pagrec
                },
                beforeSend: function () {
                    $("#" + campo).val('...');
                },
                success: function (data, textStatus, jqXHR) {
                    $("#" + campo).empty();
                    $("#" + campo).val(data);
                    if (typeof (callback) == "function") {
                        callback.call();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    tratarErroAjax(jqXHR.status, textStatus);
                }
            });
        },

        calcularValorResiduo: function (liquido, baixa, token, campo, endereco) {
            $.ajax({
                url: endereco,
                type: 'POST',
                data: {
                    liquido : liquido,
                    baixa   : baixa,
                    _token  : token
                },
                beforeSend: function () {
                    $("#" + campo).val('...');
                },
                success: function (data, textStatus, jqXHR) {
                    $("#" + campo).empty();
                    $("#" + campo).val(data);
                    if (typeof (callback) == "function") {
                        callback.call();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    tratarErroAjax(jqXHR.status, textStatus);
                }
            });
        },

        buscarDadosEmpresa: function (cnpj, url) {
            if(cnpj.length < 14 || cnpj == '__.___.___/____-__'){
                return false;
            }
            cnpj = this.limpaCampoNumerico(cnpj);
            if (!this.validarDocumento(cnpj)){
                Tela.avisoComErro("CNPJ Inválido");
                return false;
            }
            $.ajax({
                url: url+'?cnpj='+cnpj,
                success: function(retorno){
                    let dados = retorno;
                    delete dados.cnpj;
                    for (var item in dados) {
                        $('#'+item).val(dados[item]);
                        item == 'estado' ? $('#estado').trigger('change') : '';
                    }
                },
                error: function(retorno){
                    Tela.avisoComErro(retorno.responseJSON);
                }
            });

        },

        buscarDadosFlytax: function (cnpj, url) {
            if(cnpj.indexOf("/") == -1 || cnpj == '__.___.___/____-__'){
                return false;
            }
            cnpj = this.limpaCampoNumerico(cnpj);
            if (!this.validarDocumento(cnpj)){
                Tela.avisoComErro("CNPJ Inválido");
                return false;
            }
            $.ajax({
                url: url+'/'+cnpj,
                success: function(retorno){
                    let dados = retorno;
                    delete dados.cnpj;
                    for (var item in dados) {
                        $('#'+item).val(dados[item]);
                        item == 'estado' ? $('#estado').trigger('change') : '';
                    }
                },
                error: function(retorno){
                    Tela.avisoComErro(retorno.responseJSON);
                }
            });

        }
};

export default Utils;
