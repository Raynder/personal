$(document).ajaxSend(function (r, s) {
    $(".spinner").show();
});

$(document).ajaxStop(function (r, s) {
    $(".spinner").fadeOut("fast");
});

const Ajax = {
    carregarConteudo: function (endereco, componente) {
        $(".spinner").show();
        $.ajax({
            url: endereco,
            encoding: "UTF-8",
            success: function (response) {
                componente.html(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Ajax.tratarErroAjax(jqXHR.status, jqXHR.responseText);
                Tela.fecharModal();
            },
            complete: function () {
                $(".spinner").hide();
            }
        });
    },

    carregarConteudoViaPost: function (endereco, componente, dados) {
        $(".spinner").show();
        $.ajax({
            url: endereco,
            encoding: "UTF-8",
            method: "POST",
            data: dados,
            success: function (response) {
                componente.html(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Ajax.tratarErroAjax(jqXHR.status, jqXHR.responseText);
                Tela.fecharModal();
            },
            complete: function () {
                $(".spinner").hide();
            }
        });
    },

    /**
     * Este método deverá tratar os erros de ajax que ocorrerem.
     * Cada código http (400, 404, 500 e etc) deverá realizar um procedimento específico para não apresentar
     * erros diretamente ao usuário.
     * @param status
     * @param dados
     */
    tratarErroAjax: function (status, dados) {
        let msg = dados;
        if (dados instanceof Object) {
            msg = dados.responseJSON;
            if (dados.responseJSON instanceof Object) {
                msg = dados.responseJSON.message;
            }
        }

        if (status === 401) {
            Tela.avisoComAlerta(
                "Sua sessão expirou! Por favor, realize o login novamente!"
            );
        } else if (status == 500) {
            Tela.avisoComErro(msg);
        } else if (status == 403) {
            Tela.avisoComErro(
                "Acesso negado. Favor verificar com o suporte."
            );
        } else if (status == 409) {
            Tela.avisoComErro(msg);
        } else if (status == 404) {
            Tela.avisoComErro("Página não encontrada!");
        } else if (status == 422) {
            let errors = dados.responseJSON.errors;
            let errorsHtml = "<ul>";
            $.each(errors, function (key, value) {
                errorsHtml += "<li>" + value[0] + "</li>"; //showing only the first error.
            });
            errorsHtml += "</ul>";
            Tela.avisoComErro(errorsHtml);
        } else {
            Tela.avisoComErro("Erro: " + status + " Mensagem: " + msg);
        }
    },

    /**
     * Função que busca registro no servidor.
     * Normalmente usado pelo filtro nos indexes.
     * - Faz a busca e atualiza o conteudo dentro do componente 'nomeObjeto'
     *
     * - beforeSend: limpa o 'nomeObjeto' para receber os novos dados.
     *
     * - success: exibe os dados em 'nomeObjeto'
     *
     * - error: trata os erros de ajax
     * @param divLista
     * @param divLoad
     * @param endereco
     * @param metodo
     * @param dados
     * @param callback
     */
    buscarRegistros: function (
        divLista,
        endereco,
        dados,
        metodo,
        callback
    ) {
        $.ajax({
            url: endereco,
            type: metodo,
            data: dados,
            success: function (data, textStatus, jqXHR) {
                $(divLista).html(data);
                if (typeof callback == "function") {
                    callback.call();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Ajax.tratarErroAjax(jqXHR.status, jqXHR);
            }
        });
    },

    /**
     * Salvar o registro.
     * @param form
     * @param callback
     * @param showResultInScreen
     */
    salvarRegistro: function (form, callback, showResultInScreen = false) {
        let formData = $(form).serialize();
        let formUrl = $(form).attr("action");
        let footerModal = $(form).find("#footerModal");

        $.ajax({
            url: formUrl,
            type: "POST",
            data: formData,
            beforeSend: function () {
                $(footerModal)
                    .find("button[type=submit]")
                    .attr("disabled", "true");
            },
            success: function (data, textStatus, jqXHR) {
                $("#formSearch").submit();
                if (
                    !$(form)
                        .find("input[name$='continue']")
                        .is(":checked") &&
                    !showResultInScreen
                ) {
                    let buttonClose = $(form.closest('.modal-content')).find('.btn-close');
                    buttonClose.click();
                }
                $(form)[0].reset();
                if (showResultInScreen) {
                    $("#divForm_" + componente).html(data);
                }
                Tela.avisoComSucesso(data);
                if (typeof callback === "function") {
                    callback.call();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Ajax.tratarErroAjax(jqXHR.status, jqXHR);
            },
            complete: function () {
                $(footerModal)
                    .find("button[type=submit]")
                    .removeAttr("disabled");
            }
        });
    },

    /**
     * Método usado para atualizar registro diretamente de um click de botão ou
     * função javascript. Quando não será aberto um formulário de confirmação
     * @param formUrl
     * @param dadosForm
     * @param callback
     */
    salvarRegistroDireto: function (formUrl, dadosForm, method, callback) {
        let footerModal = $(form).find("#footerModal");
        $.ajax({
            url: formUrl,
            type: method,
            data: dadosForm,
            beforeSend: function () {
                $(footerModal)
                    .find("button[type=submit]")
                    .attr("disabled", "true");
            },
            success: function (data, textStatus, jqXHR) {
                Tela.avisoComSucesso(data);
                if (typeof callback === "function") {
                    callback.call();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Ajax.tratarErroAjax(jqXHR.status, jqXHR);
            },
            complete: function () {
                $(footerModal)
                    .find("button[type=submit]")
                    .removeAttr("disabled");
            }
        });
    },

    /**
     * este metodo é para enviar dados de formulário que possuírem arquivos (File)
     * o parametro dados deve ser do tipo formData
     * exemplo de uso em arquivo/anexarForm.gsp
     * acrescentado os parametros (contentType e processData)
     * para não validar os dados vindos do formulário.
     * isso impede o bloqueio por parte do javascript
     */
    salvarRegistroComArquivo: function (formulario, callback, mostrarTela) {
        if (mostrarTela === undefined) {
            mostrarTela = false;
        }
        let fd = new FormData(formulario[0]);
        let formUrl = $(formulario).attr("action");
        let componente = $(formulario).attr("component");
        let footerModal = $(formulario).find("#footerModal");

        $.ajax({
            url: formUrl,
            type: "POST",
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $(footerModal)
                    .find("button[type=submit]")
                    .attr("disabled", "true");
            },
            success: function (data, textStatus, jqXHR) {
                $("#formSearch").submit();
                if (
                    !$("input[name=continuar]").is(":checked") &&
                    !mostrarTela
                ) {
                    let buttonClose = $(formulario.closest('.modal-content')).find('.btn-close');
                    buttonClose.click();
                }
                if (!mostrarTela) {
                    $(formulario)[0].reset();
                } else {
                    $("#divForm_" + componente).html(data);
                }

                Tela.avisoComSucesso("Salvo com sucesso!");
                if (typeof callback === "function") {
                    callback.call();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Ajax.tratarErroAjax(jqXHR.status, jqXHR);
            },
            complete: function () {
                $(footerModal)
                    .find("button[type=submit]")
                    .removeAttr("disabled");
            }
        });
    },

    deletarRegistro: function () {
        let formUrl = $("#modalDelete_urlExcluir").val();
        let callback = $("#modalDelete_callback").val();
        let id = $("#modalDelete_id").val();
        // let footerModal = $(form).find("#footerModal");
        $.ajax({
            url: formUrl,
            type: "DELETE",
            beforeSend: function () {
                // $(footerModal)
                //     .find("button[type=submit]")
                //     .attr("disabled", "true");
                // console.log('ok')
            },
            success: function (data, textStatus, jqXHR) {
                $("#row_" + id).remove();
                $("#modalDelete").modal("hide");
                Tela.avisoComSucesso("Excluído com sucesso!");
                if (typeof eval(callback) == "function") {
                    callback.call();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Ajax.tratarErroAjax(jqXHR.status, jqXHR.responseJSON);
            },
            complete: function () {
                // $(footerModal)
                //     .find("button[type=submit]")
                //     .removeAttr("disabled");
                // console.log('ok')
            }
        });
    },

    deletarRegistroDireto: function (formUrl, dadosForm, method, callback) {
        let footerModal = $(form).find("#footerModal");
        $.ajax({
            url: formUrl,
            type: method,
            data: dadosForm,
            beforeSend: function () {
                $(footerModal)
                    .find("button[type=submit]")
                    .attr("disabled", "true");
            },
            success: function (data, textStatus, jqXHR) {
                $("#" + data).remove();
                if (typeof callback === "function") {
                    callback.call();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Ajax.tratarErroAjax(jqXHR.status, jqXHR);
            },
            complete: function () {
                $(footerModal)
                    .find("button[type=submit]")
                    .removeAttr("disabled");
            }
        });
    }
};

export default Ajax;
