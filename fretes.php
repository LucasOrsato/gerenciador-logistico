<?php
session_start();require_once "config/conexao.php";
$sql="SELECT f.id,f.data_frete,t.nome transportador,o.identificacao origem,d.identificacao destino,
(SELECT GROUP_CONCAT(CONCAT(p.nome,' (',fp.quantidade,')') SEPARATOR ', ') FROM frete_produtos fp JOIN produtos p ON p.id=fp.produto_id WHERE fp.frete_id=f.id) produtos
FROM fretes f JOIN transportadores t ON t.id=f.transportador_id JOIN enderecos o ON o.id=f.origem_id JOIN enderecos d ON d.id=f.destino_id ORDER BY f.data_frete DESC,f.id DESC";
$lista=$conn->query($sql);$titulo="Fretes";include "includes/cabecalho.php";?>
<div class="container py-4"><div class="d-flex justify-content-between align-items-center mb-3"><div><h1 class="h3">Fretes</h1><p class="text-muted mb-0">Consulte os fretes registrados.</p></div><a class="btn btn-primary" href="frete_novo.php">+ Novo frete</a></div>
<div class="card border-0 shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nº</th><th>Data</th><th>Transportador</th><th>Origem</th><th>Destino</th><th>Produtos e quantidades</th></tr></thead><tbody>
<?php if($lista->num_rows):while($f=$lista->fetch_assoc()): ?><tr><td>#<?= (int)$f["id"] ?></td><td><?= htmlspecialchars($f["data_frete"]) ?></td><td><?= htmlspecialchars($f["transportador"]) ?></td><td><?= htmlspecialchars($f["origem"]) ?></td><td><?= htmlspecialchars($f["destino"]) ?></td><td><?= htmlspecialchars($f["produtos"]) ?></td></tr><?php endwhile;else: ?><tr><td colspan="6" class="text-muted">Nenhum frete cadastrado.</td></tr><?php endif; ?>
</tbody></table></div></div></div></div><?php include "includes/rodape.php";?>