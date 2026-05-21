<?php
function carregarEnv(string $caminho): void {
    if (!file_exists($caminho)) {
        die("Arquivo .env não encontrado em: $caminho");
    }

    $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if ($linha === '' || str_starts_with($linha, '#')) continue;

        [$chave, $valor] = explode('=', $linha, 2);
        $chave = trim($chave);
        $valor = trim($valor);

        if (!array_key_exists($chave, $_ENV)) {
            $_ENV[$chave] = $valor;
            putenv("$chave=$valor");
        }
    }
}

carregarEnv(__DIR__ . '/../.env');

$host    = $_ENV['DB_HOST']    ?? 'localhost';
$usuario = $_ENV['DB_USUARIO'] ?? 'root';
$senha   = $_ENV['DB_SENHA']   ?? '';
$banco   = $_ENV['DB_BANCO']   ?? 'bd_requick';

try {
    $conn = new mysqli($host, $usuario, $senha, $banco);

    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }

    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    echo "Erro ao conectar com o banco de dados.<br>";
    echo "Verifique as credenciais no arquivo .env e se o MySQL está ativo.<br>";
    die("Detalhes: " . $e->getMessage());
}