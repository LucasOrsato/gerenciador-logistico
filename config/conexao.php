<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "gerenciador_logistico";
$conn = new mysqli($servidor, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro ao conectar com o banco de dados: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>