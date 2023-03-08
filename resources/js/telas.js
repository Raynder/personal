let janela = window;
let tipoJanela = "inner";
let larguraJanela = 0;
let alturaJanela = 0;

const Tela = {

    pegaTamanhoJanela: function () {
        if (!("innerWidth" in window)) {
            tipoJanela = "client";
            janela = document.documentElement || document.body;
        }

        larguraJanela = janela[tipoJanela + "Width"];
        alturaJanela = janela[tipoJanela + "Height"];
    },

    /**
     * Fecha a modal aberta.
     * Passar a div principal como parametro. Ex.: <div class="modal-geral"></div>
     * @param dlg
     */
    fecharModal: function (dlg) {
        var b = $(dlg).find(
            ".modal-dialog > .modal-content > .modal-header > button.btn-close"
        );
        b.click();
    },

    /**
     * Metodo para abrir os popups com as janelas de create, edit e etc.
     *
     * - endereco que sera carregado
     * - titulo do popup
     * - grande, sim ou nao. Tamanho do popup
     *
     * @param endereco
     * @param titulo
     * @param tamanho (P,M,G)
     */
    abrirJanela: function (endereco, titulo, tamanho) {
        Tela.pegaTamanhoJanela();
        let sizeClass = "modal-" + tamanho;
        if (larguraJanela < 992) {
            if (!$("#modalBasic > div.modal-dialog").hasClass("modal-xl")) {
                $("#modalBasic > div.modal-dialog").addClass("modal-xl");
            }
        } else {
            if ($("#modalBasic > div.modal-dialog").hasClass("modal-lg")) {
                $("#modalBasic > div.modal-dialog").removeClass("modal-lg");
            }
            if ($("#modalBasic > div.modal-dialog").hasClass("modal-sm")) {
                $("#modalBasic > div.modal-dialog").removeClass("modal-sm");
            }
            $("#modalBasic > div.modal-dialog").addClass(sizeClass);
        }

        $("#modalBasic > div.modal-dialog > div.modal-content > div.modal-body").html("");
        $("#modalBasic > div.modal-dialog > div.modal-content > div.modal-header > h5.modal-title").html(titulo);

        Ajax.carregarConteudo(
            endereco,
            $("#modalBasic > div.modal-dialog > div.modal-content > div.modal-body")
        );
        $("#modalBasic").modal("show");
    },

    // Abrir a janela passando os dados via post
    abrirJanelaPost: function (url, titulo, dados, tamanho) {
        Tela.pegaTamanhoJanela();
        let sizeClass = "modal-" + tamanho;
        if (larguraJanela < 992) {
            if (!$("#modalBasic > div.modal-dialog").hasClass("modal-xl")) {
                $("#modalBasic > div.modal-dialog").addClass("modal-xl");
            }
        } else {
            if ($("#modalBasic > div.modal-dialog").hasClass("modal-lg")) {
                $("#modalBasic > div.modal-dialog").removeClass("modal-lg");
            }
            if ($("#modalBasic > div.modal-dialog").hasClass("modal-sm")) {
                $("#modalBasic > div.modal-dialog").removeClass("modal-sm");
            }
            $("#modalBasic > div.modal-dialog").addClass(sizeClass);
        }

        $("#modalBasic > div.modal-dialog > div.modal-content > div.modal-body").html("");
        $("#modalBasic > div.modal-dialog > div.modal-content > div.modal-header > h5.modal-title").html(titulo);

        Ajax.carregarConteudoViaPost(url, $("#modalBasic > div.modal-dialog > div.modal-content > div.modal-body"), dados);
        $("#modalBasic").modal("show");
    },


    /**
     * Metodo para abrir os popups com as janelas de create, edit e etc.
     *
     * - endereco que sera carregado
     * - titulo do popup
     * - grande, sim ou nao. Tamanho do popup
     *
     * @param endereco
     * @param titulo
     * @param tamanho (são os tamanhos do bootstrap)
     */
    abrirSubJanela: function (endereco, titulo, tamanho) {
        Tela.pegaTamanhoJanela();
        let sizeClass = "modal-" + tamanho;
        var modalDialog = $("#modalSub > div.modal-dialog");
        if (larguraJanela < 992) {
            if (!modalDialog.hasClass("modal-xl")) {
                modalDialog.addClass("modal-xl");
            }
        } else {
                if ($(modalDialog).hasClass("modal-lg")) {
                    $(modalDialog).removeClass("modal-lg");
                }
                if ($(modalDialog).hasClass("modal-sm")) {
                    $(modalDialog).removeClass("modal-sm");
                }

            $(modalDialog).addClass(sizeClass);
        }

        $("#modalSub > div.modal-dialog > div.modal-content > div.modal-body").html("");
        $("#modalSub > div.modal-dialog > div.modal-content > div.modal-header > h5.modal-title").html(titulo);

        Ajax.carregarConteudo(endereco, $("#modalSub > div.modal-dialog > div.modal-content > div.modal-body"));
        $("#modalSub").modal('show');
    },

    /**
     * Abre o modal para exclusão do registro
     * @param url
     */
    abrirJanelaExcluir: function (url, id, callback) {
        let btn =
            '<button class="btn btn-danger btn-sm" onclick="Ajax.deletarRegistro()" type="button"><i class="fa fa-save"></i> Confirmar Exclusão</button>';
        btn +=
            ' <button class="btn btn-warning btn-sm" data-bs-dismiss="modal" type="button"><i class="fa fa-times"></i> Fechar</button>';
        $("#modalDelete_urlExcluir").val(url);
        $("#modalDelete_callback").val(callback);
        $("#modalDelete_id").val(id);
        $("#modalDelete_footerDeletar").html(btn);
        $("#modalDelete").modal("show");
    },


    /**
     * Função para alertar o usuário com um balão contendo a mensagem desejada
     *
     * @param mensagem
     * @param tipo          : info, sucesso, alerta ou erro.
     */
    aviso: function (mensagem, tipo) {
        let position = "topRight";
        if (tipo === "erro") {
            iziToast.error({
                message: mensagem,
                position: position
            });
        } else if (tipo === "sucesso") {
            iziToast.success({
                message: mensagem,
                position: position
            });
        } else if (tipo === "alerta") {
            iziToast.warning({
                message: mensagem,
                position: position
            });
        } else {
            iziToast.show({
                message: mensagem,
                position: position
            });
        }
    },

    avisoComErro: function (mensagem) {
        Tela.aviso(mensagem, "erro");
    },

    avisoComInformacao: function (mensagem) {
        Tela.aviso(mensagem, "info");
    },

    avisoComAlerta: function (mensagem) {
        Tela.aviso(mensagem, "alerta");
    },

    avisoComSucesso: function (mensagem) {
        Tela.aviso(mensagem, "sucesso");
    },

    /**
     * Selecionar ou desmarcar todos os checkbox passados no parâmetro.
     * nomeCheckBoxLista =      O nome de todos os checkbox que devem ser selecionados ao clicar em componenteSelecionavel
     * nomeCheckBoxLista:       Deve ser passado o nome da classe CSS para buscar os componentes.
     * componenteSelecionavel:  Deve ser passado o ID único do componente.
     */
    selecionarTodosOsCheckBoxes: function (
        nomeCheckBoxLista,
        componenteSelecionavel
    ) {
        var boxes = document.getElementsByClassName(nomeCheckBoxLista);
        var i = 0;
        for (i; i < boxes.length; i++) {
            boxes[i].checked = document.getElementById(
                componenteSelecionavel
            ).checked;

            var tr = $(boxes[i])
                .parent()
                .parent();
            if ($(boxes[i]).is(":checked")) {
                $(tr).addClass("success");
            } else {
                $(tr).removeClass("success");
            }
        }
    }
};


export default Tela;
