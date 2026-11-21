<?php
session_start(); require_once "config/conexao.php";
if($_SERVER["REQUEST_METHOD"]==="POST"){
 $id=(int)($_POST["id"]??0); $nome=trim($_POST["nome"]??""); $descricao=trim($_POST["descricao"]??""); $quantidade=(int)($_POST["quantidade"]??0); $peso=(float)($_POST["peso"]??0); $valor=(float)($_POST["valor"]??0);
 if($nome!=="" && $quantidade>=0 && $peso>=0 && $valor>=0){
  if($id){$st=$conn->prepare("UPDATE produtos SET nome=?,descricao=?,quantidade=?,peso=?,valor=? WHERE id=?");$st->bind_param("ssiddi",$nome,$descricao,$quantidade,$peso,$valor,$id);}
  else{$st=$conn->prepare("INSERT INTO produtos(nome,descricao,quantidade,peso,valor) VALUES(?,?,?,?,?)");$st->bind_param("ssidd",$nome,$descricao,$quantidade,$peso,$valor);}
  $st->execute(); $_SESSION["mensagem"]="Produto salvo com sucesso.";
 } else $_SESSION["mensagem"]="Preencha o nome e informe valores válidos.";
 header("Location: produtos.php");exit;
}
if(isset($_GET["excluir"])){ $id=(int)$_GET["excluir"]; $st=$conn->prepare("DELETE FROM produtos WHERE id=?");$st->bind_param("i",$id);$st->execute();$_SESSION["mensagem"]="Produto excluído (se não estiver vinculado a um frete).";header("Location: produtos.php");exit;}
$editar=null;if(isset($_GET["editar"])){$id=(int)$_GET["editar"];$st=$conn->prepare("SELECT * FROM produtos WHERE id=?");$st->bind_param("i",$id);$st->execute();$editar=$st->get_result()->fetch_assoc();}
$lista=$conn->query("SELECT * FROM produtos ORDER BY nome");$titulo="Produtos";include "includes/cabecalho.php";
?>
<div class="container py-4"><div class="d-flex justify-content-between align-items-center mb-3"><div><h1 class="h3">Produtos</h1><p class="text-muted">Cadastre e edite os produtos transportados.</p></div></div>
<div class="card border-0 shadow-sm mb-4"><div class="card-body"><h2 class="h5"><?= $editar?"Editar produto":"Novo produto" ?></h2>
<form method="post" class="row g-3"><input type="hidden" name="id" value="<?= (int)($editar["id"]??0) ?>">
<div class="col-md-6"><label class="form-label">Nome *</label><input class="form-control" name="nome" required value="<?= htmlspecialchars($editar["nome"]??"") ?>"></div>
<div class="col-md-6"><label class="form-label">Descrição</label><input class="form-control" name="descricao" value="<?= htmlspecialchars($editar["descricao"]??"") ?>"></div>
<div class="col-md-4"><label class="form-label">Quantidade em estoque</label><input type="number" min="0" class="form-control" name="quantidade" value="<?= htmlspecialchars($editar["quantidade"]??"0") ?>"></div>
<div class="col-md-4"><label class="form-label">Peso (kg)</label><input type="number" min="0" step="0.01" class="form-control" name="peso" value="<?= htmlspecialchars($editar["peso"]??"0") ?>"></div>
<div class="col-md-4"><label class="form-label">Valor (R$)</label><input type="number" min="0" step="0.01" class="form-control" name="valor" value="<?= htmlspecialchars($editar["valor"]??"0") ?>"></div>
<div class="col-12"><button class="btn btn-primary">Salvar produto</button><?php if($editar): ?> <a class="btn btn-outline-secondary" href="produtos.php">Cancelar</a><?php endif; ?></div></form></div></div>
<div class="card border-0 shadow-sm"><div class="card-body"><h2 class="h5">Produtos cadastrados</h2><div class="table-responsive"><table class="table"><thead><tr><th>Nome</th><th>Quantidade</th><th>Peso</th><th>Valor</th><th>Ações</th></tr></thead><tbody>
<?php while($p=$lista->fetch_assoc()): ?><tr><td><?= htmlspecialchars($p["nome"]) ?></td><td><?= (int)$p["quantidade"] ?></td><td><?= number_format($p["peso"],2,",",".") ?> kg</td><td>R$ <?= number_format($p["valor"],2,",",".") ?></td><td><a href="?editar=<?= $p["id"] ?>">Editar</a> · <a onclick="return confirm('Excluir este produto?')" href="?excluir=<?= $p["id"] ?>">Excluir</a></td></tr><?php endwhile; ?>
</tbody></table></div></div></div></div><?php include "includes/rodape.php"; ?>