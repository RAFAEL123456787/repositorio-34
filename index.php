<?php

$host = "localhost";
$banco = "techstore";
$usuario = "root";
$senha = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    echo "Banco conectado com sucesso!";

} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>