<?php if (!isset($titulo)) { $titulo = "LogiControl"; } ?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($titulo) ?> | LogiControl</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="css/estilo.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark nav-logi">
 <div class="container"><a class="navbar-brand fw-bold" href="index.php">LogiControl</a>
 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"><span class="navbar-toggler-icon"></span></button>
 <div class="collapse navbar-collapse" id="menu"><ul class="navbar-nav ms-auto">
 <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
 <li class="nav-item"><a class="nav-link" href="produtos.php">Produtos</a></li>
 <li class="nav-item"><a class="nav-link" href="enderecos.php">Endereços</a></li>
 <li class="nav-item"><a class="nav-link" href="transportadores.php">Transportadores</a></li>
 <li class="nav-item"><a class="nav-link" href="fretes.php">Fretes</a></li>
 <li class="nav-item"><a class="nav-link" href="relatorios.php">Relatórios</a></li>
 </ul></div></div>
</nav>
<?php if (!empty($_SESSION["mensagem"])): ?><div class="container mt-3"><div class="alert alert-info alert-dismissible fade show"><?= htmlspecialchars($_SESSION["mensagem"]) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div><?php unset($_SESSION["mensagem"]); endif; ?>