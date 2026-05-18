<?php
$host = 'localhost';      // ← banco local (mesma máquina)
$usuario = 'root';
$senha = '';              // padrão do XAMPP/phpMyAdmin é sem senha
$banco = 'bd_requick';

try {
    $conn = new mysqli($host, $usuario, $senha, $banco);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>