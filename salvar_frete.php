
<?php
session_start();

require_once "config/conexao.php";

// Só permite acessar esta página pelo envio do formulário
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: frete_novo.php");
    exit;
}

// Recebe os dados do formulário
$transportador = (int)($_POST["transportador_id"] ?? 0);
$origem = (int)($_POST["origem_id"] ?? 0);
$destino = (int)($_POST["destino_id"] ?? 0);
$data = $_POST["data_frete"] ?? "";

$ids = $_POST["produto_id"] ?? [];
$quantidades = $_POST["quantidade"] ?? [];

// Função para voltar ao formulário com uma mensagem
function voltarFormulario($mensagem) {
    $_SESSION["mensagem"] = $mensagem;
    header("Location: frete_novo.php");
    exit;
}

// Verifica se os campos principais foram preenchidos
if (
    $transportador <= 0 ||
    $origem <= 0 ||
    $destino <= 0 ||
    $origem === $destino ||
    empty($data)
) {
    voltarFormulario("Confira os dados do frete. Origem e destino devem ser diferentes.");
}

// Verifica se os produtos e as quantidades foram enviados corretamente
if (
    !is_array($ids) ||
    !is_array($quantidades) ||
    count($ids) === 0 ||
    count($ids) !== count($quantidades)
) {
    voltarFormulario("Adicione pelo menos um produto e informe sua quantidade.");
}

// Valida o formato e a existência da data
$dataValida = DateTime::createFromFormat("!Y-m-d", $data);

if (!$dataValida || $dataValida->format("Y-m-d") !== $data) {
    voltarFormulario("Informe uma data válida para o frete.");
}

// Verifica se o transportador existe
$consulta = $conn->prepare(
    "SELECT id FROM transportadores WHERE id = ?"
);
$consulta->bind_param("i", $transportador);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    voltarFormulario("O transportador selecionado não foi encontrado.");
}

// Verifica se o endereço de origem é realmente uma origem
$consulta = $conn->prepare(
    "SELECT id FROM enderecos
     WHERE id = ? AND tipo IN ('Origem', 'Ambos')"
);
$consulta->bind_param("i", $origem);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    voltarFormulario("O endereço selecionado não pode ser utilizado como origem.");
}

// Verifica se o endereço de destino é realmente um destino
$consulta = $conn->prepare(
    "SELECT id FROM enderecos
     WHERE id = ? AND tipo IN ('Destino', 'Ambos')"
);
$consulta->bind_param("i", $destino);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    voltarFormulario("O endereço selecionado não pode ser utilizado como destino.");
}

// Verifica se cada produto existe e se a quantidade é válida
$consultaProduto = $conn->prepare(
    "SELECT id FROM produtos WHERE id = ?"
);

foreach ($ids as $i => $produto) {

    if (
        !isset($quantidades[$i]) ||
        !filter_var($produto, FILTER_VALIDATE_INT) ||
        !filter_var($quantidades[$i], FILTER_VALIDATE_INT)
    ) {
        voltarFormulario("Verifique os produtos e as quantidades informadas.");
    }

    $produtoId = (int)$produto;
    $quantidade = (int)$quantidades[$i];

    if ($produtoId <= 0 || $quantidade <= 0) {
        voltarFormulario("A quantidade dos produtos deve ser maior que zero.");
    }

    $consultaProduto->bind_param("i", $produtoId);
    $consultaProduto->execute();
    $resultadoProduto = $consultaProduto->get_result();

    if ($resultadoProduto->num_rows === 0) {
        voltarFormulario("Um dos produtos selecionados não foi encontrado.");
    }
}

// Se todas as validações passaram, registra o frete
$conn->begin_transaction();

try {

    // Cadastra os dados principais do frete
    $st = $conn->prepare(
        "INSERT INTO fretes
        (data_frete, transportador_id, origem_id, destino_id)
        VALUES (?, ?, ?, ?)"
    );

    $st->bind_param(
        "siii",
        $data,
        $transportador,
        $origem,
        $destino
    );

    $st->execute();

    // Recupera o ID do frete criado
    $freteId = $conn->insert_id;

    // Prepara o cadastro dos produtos relacionados ao frete
    $item = $conn->prepare(
        "INSERT INTO frete_produtos
        (frete_id, produto_id, quantidade)
        VALUES (?, ?, ?)"
    );

    // Registra todos os produtos do frete
    foreach ($ids as $i => $produto) {

        $produtoId = (int)$produto;
        $quantidade = (int)$quantidades[$i];

        $item->bind_param(
            "iii",
            $freteId,
            $produtoId,
            $quantidade
        );

        $item->execute();
    }

    // Confirma o cadastro
    $conn->commit();

    $_SESSION["mensagem"] = "Frete #" . $freteId . " gerado com sucesso!";

} catch (Throwable $e) {

    // Desfaz o cadastro se ocorrer algum erro
    $conn->rollback();

    $_SESSION["mensagem"] = "Não foi possível gerar o frete. Confira os dados e tente novamente.";
}

// Volta para a lista de fretes
header("Location: fretes.php");
exit;
?>
