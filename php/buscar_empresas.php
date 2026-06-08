<?php
require_once 'auth.php';
require_once 'projeto_acoes.php';

header('Content-Type: application/json');

$tipo_usuario = $_SESSION['usuario_tipo'] ?? 'Cliente';
if (!in_array($tipo_usuario, ['Administrador', 'Desenvolvedor'])) {
    echo json_encode([]);
    exit;
}

$busca = trim($_GET['q'] ?? '');

try {
    $pdo  = \php\Conexao::getConn();
    $stmt = $pdo->prepare("
        SELECT id, nome_empresa
        FROM tb_empresa
        WHERE nome_empresa LIKE :busca
        ORDER BY nome_empresa ASC
        LIMIT 10
    ");
    $stmt->execute([':busca' => '%' . $busca . '%']);
    echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
} catch (\Exception $e) {
    echo json_encode([]);
}