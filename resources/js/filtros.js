const Filtro = {
    inicializaCampoBusca: function (
        url,
        componente,
        texto_place_holder,
        modal = "document",
        parametros
    ) {
        $(componente).select2({
            dropdownParent: $(modal),
            placeholder: texto_place_holder,
            allowClear: true,
            minimumInputLength: 2,
            language: "pt-BR",
            ajax: {
                url: url,
                dataType: "json",
                data: function (params) {
                    var obj = {
                        q: params.term,
                        page_limit: 20,
                        quem: $(componente).attr("id")
                    };
                    if (parametros !== undefined) {
                        jQuery.extend(obj, parametros.call());
                    }
                    return obj;
                },
                processResults: function (data) {
                    return { results: data };
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });
    },

    inicializaFormBusca: function (
        formulario,
        divLista,
        buscarAutomaticamente
    ) {
        $(formulario).submit(function (e) {
            var formUrl = $(this).attr("action");
            var postData = $(this).serializeArray();
            Ajax.buscarRegistros(
                divLista,
                formUrl,
                postData,
                "POST",
                Filtro.inicializaPaginacaoOrdenacao.bind(
                    this,
                    divLista,
                    formUrl
                )
            );
            e.preventDefault();
        });
        if (buscarAutomaticamente === true) {
            $(formulario).submit();
        }
    },

    inicializaPaginacaoOrdenacao: function (divLista, formUrl, divLoad) {
        $(divLista)
            .find(".pagination a, .pagination-sm a, th.sortable a")
            .on("click", function (event) {
                event.preventDefault();
                var url =
                    formUrl +
                    "?" +
                    $(this)
                        .attr("href")
                        .split("?")[1];
                var dados = {};

                Ajax.buscarRegistros(
                    divLista,
                    url,
                    dados,
                    "POST",
                    Filtro.inicializaPaginacaoOrdenacao.bind(
                        this,
                        divLista,
                        formUrl
                    )
                );
            });
    }
};

export default Filtro;
