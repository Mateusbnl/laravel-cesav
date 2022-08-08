function adicionaContratoNaTabela(contratos, unidade_escolhida, produto_escolhido) {
    var tabela = document.querySelector("#corpo");
    var display_unidade = document.querySelector("#display_unidade");
    var display_produto = document.querySelector("#display_produto");

    tabela.innerHTML = '';

    if (unidade_escolhida == "todas_unidades") {
        unidade_escolhida = 1;
    }

    if (unidade_escolhida == "todos_produtos") {
        produto_escolhido = 1;
    }

    const codigos_unidades = [...new Set(contratos.map(contrato => contrato.co_unidade))];
    const codigos_produtos = [...new Set(contratos.map(contrato => contrato.nu_produto))];

    codigos_unidades.forEach(function (contrato) {
        var opcao = montaOption(contrato.co_unidade, contrato.co_unidade);
        display_unidade.appendChild(opcao);
    });

    codigos_produtos.forEach(function (contrato) {
        var opcao = montaOption(contrato.nu_produto, nu_produto);
        display_produto.appendChild(opcao);
    });


    contratos = contratos.filter(contrato => contrato.co_unidade = unidade_escolhida)
    contratos = contratos.filter(contrato => contrato.nu_produto = produto_escolhido)

    contratos.forEach(function (contrato) {
        var contratoTr = montaTr(contrato);
        tabela.appendChild(contratoTr);
    });

}

function montaOption(texto_opcao, valor) {
    var opcao = document.createElement("option");
    opcao.innerHTML = texto_opcao;
    opcao.setAttribute("value", valor);
    return opcao;
}

function montaTr(contrato) {
    var contratoTr = document.createElement("tr");
    var coluna = document.createElement("th");
    coluna.setAttribute("scope", "row");
    coluna.textContent = contrato.data_arquivo;

    contratoTr.appendChild(coluna);
    contratoTr.appendChild(montaTd(contrato.qtd, "info-qtd"));
    contratoTr.appendChild(montaTd(contrato.valor_base, "info-valor"));

    return contratoTr;
}

function montaTd(dado, classe) {
    var td = document.createElement("td");
    td.classList.add(classe);
    td.textContent = dado;

    return td;
}