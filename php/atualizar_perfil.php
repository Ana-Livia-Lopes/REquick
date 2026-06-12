<?php
require_once 'auth.php';
$pdo = require_once '../config/conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido.']);
    exit;
}

$idUsuario = $_SESSION['usuario_id']  ?? null;

if (!$idUsuario) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Sessão inválida.']);
    exit;
}

$nome           = trim($_POST['nome']           ?? '');
$email          = trim($_POST['email']          ?? '');
$especializacao = trim($_POST['especializacao'] ?? '');

if (!$nome || !$email) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nome e e-mail são obrigatórios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail inválido.']);
    exit;
}

/* verifica se o e-mail já existe para outro usuário */
$stmtCheck = $pdo->prepare("SELECT id FROM tb_usuarios WHERE email = :email AND id != :id");
$stmtCheck->execute([':email' => $email, ':id' => $idUsuario]);

if ($stmtCheck->fetch()) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Este e-mail já está em uso.']);
    exit;
}

$emailAtual    = $_SESSION['usuario_email'] ?? '';
$emailAlterado = ($email !== $emailAtual);

$stmt = $pdo->prepare(
    "UPDATE tb_usuarios SET nome = :nome, email = :email, especializacao = :esp WHERE id = :id"
);
$stmt->execute([
    ':nome'  => $nome,
    ':email' => $email,
    ':esp'   => $especializacao,
    ':id'    => $idUsuario,
]);

$_SESSION['usuario_nome']  = $nome;
$_SESSION['usuario_email'] = $email;

echo json_encode([
    'sucesso'       => true,
    'emailAlterado' => $emailAlterado,
    'mensagem'      => 'Perfil atualizado com sucesso.',
]);