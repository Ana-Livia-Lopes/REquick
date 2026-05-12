<?php
// Use o IP Público (IPv4) que aparece no painel da instância EC2
$host = 'localhost'; 
$usuario = 'root'; 
$senha = '';
$banco = 'bd_requick';

try {
    $conn = new mysqli($host, $usuario, $senha, $banco);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    echo "Erro: Verifique se a porta 3306 está aberta no Security Group da AWS.<br>";
    die("Detalhes: " . $e->getMessage());
}
?>