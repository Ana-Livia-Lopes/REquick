<?php
$host    = 'localhost';
$usuario = 'root';
$senha   = '';
$banco   = 'bd_requick';

try {
    $pdo = new \PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (\PDOException $e) {
    echo "Erro ao conectar com o banco de dados.<br>";
    echo "Verifique se o MySQL está ativo e as credenciais estão corretas.<br>";
    die("Detalhes: " . $e->getMessage());
}

// Retorna a instância para quem fizer require_once deste arquivo
return $pdo;