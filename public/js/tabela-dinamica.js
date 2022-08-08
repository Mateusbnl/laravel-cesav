function adicionaContratoNaTabela(contratos, unidade_escolhida, produto_escolhido, data_escolhida) {
    if (unidade_escolhida == "todas_unidades") {
        unidade_escolhida = 1;
    }

    if (produto_escolhido == "todos_produtos") {
        produto_escolhido = 1;
    }

    var tabela = document.querySelector("#corpo");
    var display_unidade = document.querySelector("#display_unidade");
    var display_produto = document.querySelector("#display_produto");
    var display_data = document.querySelector("#display_data");

    tabela.innerHTML = '';

    const codigos_unidades = [...new Set(contratos.map(contrato => contrato.co_unidade))];
    const codigos_produtos = [...new Set(contratos.map(contrato => contrato.nu_produto))];
    const datas_validas = [...new Set(contratos.map(contrato => contrato.data_arquivo))];

    codigos_unidades.forEach(function (contrato) {
        var opcao = montaOption(contrato, contrato);
        display_unidade.appendChild(opcao);
    });

    codigos_produtos.forEach(function (contrato) {
        var opcao = montaOption(contrato, contrato);
        display_produto.appendChild(opcao);
    });

    datas_validas.forEach(function (contrato) {
        var opcao = montaOption(contrato, contrato);
        display_data.appendChild(opcao);
    });

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
    contratoTr.appendChild(montaTd(contrato.nu_produto, "info-produto"));
    contratoTr.appendChild(montaTd(contrato.qtd, "info-qtd"));
    contratoTr.appendChild(montaTd(contrato.valor_base, "info-valor"));
    contratoTr.appendChild(montaTd(contrato.co_unidade, "info-unidade"));

    return contratoTr;
}

function montaTd(dado, classe) {
    var td = document.createElement("td");
    td.classList.add(classe);
    td.textContent = dado;

    return td;
}