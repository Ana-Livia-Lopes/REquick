<?php
require_once 'auth.php';
$pdo = require_once '../config/conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido.']);
    exit;
}

$idUsuario   = $_SESSION['usuario_id']   ?? null;
$tipoUsuario = $_SESSION['usuario_tipo'] ?? '';

if (!$idUsuario || strtolower($tipoUsuario) !== 'administrador') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
    exit;
}

$senhaAtual     = $_POST['senha_atual']     ?? '';
$novaSenha      = $_POST['nova_senha']      ?? '';
$confirmarSenha = $_POST['confirmar_senha'] ?? '';

if (!$senhaAtual || !$novaSenha || !$confirmarSenha) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos.']);
    exit;
}

if ($novaSenha !== $confirmarSenha) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'A nova senha e a confirmação não coincidem.']);
    exit;
}

if (strlen($novaSenha) < 6) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'A nova senha deve ter pelo menos 6 caracteres.']);
    exit;
}

$stmt = $pdo->prepare("SELECT senha FROM tb_usuarios WHERE id = :id");
$stmt->execute([':id' => $idUsuario]);
$row = $stmt->fetch();

if (!$row) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não encontrado.']);
    exit;
}

$hashAtual = $row['senha'];

$senhaCorreta = password_verify($senhaAtual, $hashAtual)
             || hash('sha256', $senhaAtual) === $hashAtual;

if (!$senhaCorreta) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Senha atual incorreta.']);
    exit;
}

$novoHash = password_hash($novaSenha, PASSWORD_BCRYPT);

$stmtUp = $pdo->prepare("UPDATE tb_usuarios SET senha = :senha WHERE id = :id");
$stmtUp->execute([':senha' => $novoHash, ':id' => $idUsuario]);

echo json_encode(['sucesso' => true, 'mensagem' => 'Senha alterada. Você será desconectado.']);