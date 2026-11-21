function adicionarProduto(){
 const area=document.getElementById("lista-produtos");
 if(!area) return;
 const linha=document.querySelector(".linha-produto").cloneNode(true);
 linha.querySelector("select").selectedIndex=0;
 linha.querySelector("input").value=1;
 area.appendChild(linha);
}
function removerProduto(botao){
 const linhas=document.querySelectorAll(".linha-produto");
 if(linhas.length>1){botao.closest(".linha-produto").remove();}
}
