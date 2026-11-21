<?php
session_start();
require_once "config/conexao.php";
$totalProdutos = $conn->query("SELECT COUNT(*) AS total FROM produtos")->fetch_assoc()["total"];
$totalTransportadores = $conn->query("SELECT COUNT(*) AS total FROM transportadores")->fetch_assoc()["total"];
$totalFretes = $conn->query("SELECT COUNT(*) AS total FROM fretes")->fetch_assoc()["total"];
$fretesRecentes = $conn->query("SELECT f.id, f.data_frete, t.nome AS transportador, o.cidade AS origem, d.cidade AS destino
FROM fretes f JOIN transportadores t ON t.id=f.transportador_id
JOIN enderecos o ON o.id=f.origem_id JOIN enderecos d ON d.id=f.destino_id
ORDER BY f.id DESC LIMIT 5");
include "includes/cabecalho.php";
?>
<div class="container py-4">
  <div class="p-4 p-md-5 mb-4 hero rounded-4">
    <h1 class="display-6 fw-bold">Gerenciador Logístico</h1>
    <p class="lead mb-0">Organize produtos, transportadores, endereços e fretes em um só lugar.</p>
  </div>
  <div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card metric-card"><div class="card-body"><p class="text-muted mb-1">Produtos cadastrados</p><div class="metric"><?= (int)$totalProdutos ?></div><a href="produtos.php">Ver produtos</a></div></div></div>
    <div class="col-md-4"><div class="card metric-card"><div class="card-body"><p class="text-muted mb-1">Transportadores</p><div class="metric"><?= (int)$totalTransportadores ?></div><a href="transportadores.php">Ver transportadores</a></div></div></div>
    <div class="col-md-4"><div class="card metric-card"><div class="card-body"><p class="text-muted mb-1">Fretes registrados</p><div class="metric"><?= (int)$totalFretes ?></div><a href="fretes.php">Ver fretes</a></div></div></div>
  </div>
  <div class="card border-0 shadow-sm"><div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0">Fretes recentes</h2><a class="btn btn-primary btn-sm" href="frete_novo.php">+ Novo frete</a></div>
    <?php if ($fretesRecentes->num_rows): ?>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nº</th><th>Data</th><th>Transportador</th><th>Origem</th><th>Destino</th></tr></thead><tbody>
    <?php while($f=$fretesRecentes->fetch_assoc()): ?><tr><td>#<?= (int)$f["id"] ?></td><td><?= htmlspecialchars($f["data_frete"]) ?></td><td><?= htmlspecialchars($f["transportador"]) ?></td><td><?= htmlspecialchars($f["origem"]) ?></td><td><?= htmlspecialchars($f["destino"]) ?></td></tr><?php endwhile; ?>
    </tbody></table></div><?php else: ?><p class="text-muted mb-0">Ainda não existem fretes cadastrados. Cadastre os dados e crie o primeiro frete.</p><?php endif; ?>
  </div></div>
</div>
<?php include "includes/rodape.php"; ?>