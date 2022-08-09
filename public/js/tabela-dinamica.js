
function adicionaContratoNaTabela(contratos) {

    var tabela = document.querySelector("#corpo");

    tabela.innerHTML = '';

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