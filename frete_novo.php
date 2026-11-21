
<?php
session_start();
require_once "config/conexao.php";

$produtos = $conn->query("SELECT id, nome FROM produtos ORDER BY nome");

$origens = $conn->query("SELECT id, identificacao, cidade, estado FROM enderecos WHERE tipo IN ('Origem', 'Ambos') ORDER BY identificacao");

$destinos = $conn->query("SELECT id, identificacao, cidade, estado FROM enderecos WHERE tipo IN ('Destino', 'Ambos') ORDER BY identificacao");

$transportadores = $conn->query("SELECT id, nome FROM transportadores ORDER BY nome");

$titulo = "Novo frete";
include "includes/cabecalho.php";
?>

<div class="container py-4">

    <h1 class="h3">Novo frete</h1>
    <p class="text-muted">Relacione o transportador, os endereços e um ou mais produtos.</p>

    <?php if ($produtos->num_rows == 0 || $origens->num_rows == 0 || $destinos->num_rows == 0 || $transportadores->num_rows == 0): ?>
        <div class="alert alert-warning">
            Antes de criar um frete, cadastre pelo menos um produto, um endereço de origem, um endereço de destino e um transportador.
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form method="post" action="salvar_frete.php" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Data do frete</label>
                    <input type="date" name="data_frete" class="form-control" required value="<?= date("Y-m-d") ?>">
                </div>

                <div class="col-md-8">
                    <label class="form-label">Transportador</label>
                    <select name="transportador_id" class="form-select" required>
                        <option value="">Selecione</option>
                        <?php while ($t = $transportadores->fetch_assoc()): ?>
                            <option value="<?= $t["id"] ?>">
                                <?= htmlspecialchars($t["nome"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Origem</label>
                    <select name="origem_id" class="form-select" required>
                        <option value="">Selecione</option>
                        <?php while ($e = $origens->fetch_assoc()): ?>
                            <option value="<?= $e["id"] ?>">
                                <?= htmlspecialchars($e["identificacao"] . " — " . $e["cidade"] . "/" . $e["estado"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Destino</label>
                    <select name="destino_id" class="form-select" required>
                        <option value="">Selecione</option>
                        <?php while ($e = $destinos->fetch_assoc()): ?>
                            <option value="<?= $e["id"] ?>">
                                <?= htmlspecialchars($e["identificacao"] . " — " . $e["cidade"] . "/" . $e["estado"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-12">

                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label">Produtos do frete</label>

                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="adicionarProduto()">
                            + Adicionar produto
                        </button>
                    </div>

                    <div id="lista-produtos">

                        <div class="row g-2 linha-produto mb-2">

                            <div class="col-md-8">
                                <select name="produto_id[]" class="form-select" required>
                                    <option value="">Selecione um produto</option>

                                    <?php
                                    $produtos->data_seek(0);
                                    while ($p = $produtos->fetch_assoc()):
                                    ?>
                                        <option value="<?= $p["id"] ?>">
                                            <?= htmlspecialchars($p["nome"]) ?>
                                        </option>
                                    <?php endwhile; ?>

                                </select>
                            </div>

                            <div class="col-md-2">
                                <input type="number" name="quantidade[]" min="1" value="1" class="form-control" required>
                            </div>

                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100" onclick="removerProduto(this)">
                                    Remover
                                </button>
                            </div>

                        </div>

                    </div>

                    <small class="text-muted">
                        O mesmo transportador pode levar vários produtos no mesmo frete.
                    </small>

                </div>

                <div class="col-12">

                    <button class="btn btn-primary"
                        <?= ($produtos->num_rows == 0 || $origens->num_rows == 0 || $destinos->num_rows == 0 || $transportadores->num_rows == 0) ? "disabled" : "" ?>>
                        Gerar frete
                    </button>

                    <a href="fretes.php" class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<?php include "includes/rodape.php"; ?>
