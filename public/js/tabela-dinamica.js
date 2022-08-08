function adicionaContratoNaTabela(contratos) {
    var tabela = document.querySelector("#corpo");

    contratos.forEach(function (contrato) {
        var contratoTr = montaTr(contrato);
        tabela.appendChild(contratoTr);
    });

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