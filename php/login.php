<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

if (!empty($_SESSION['usuario_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$pdo = require_once __DIR__ . '/../config/conexao.php';

// 1. Coleta e sanitiza
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// 2. Valida campos vazios
if (empty($email) || empty($senha)) {
    header("Location: ../index.php?erro=campos_vazios");
    exit;
}

// 3. Busca usuário pelo e-mail
$stmt = $pdo->prepare("SELECT * FROM tb_usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: ../index.php?erro=credenciais_invalidas&email=" . urlencode($email));
    exit;
}

// 4. Verifica senha suportando os dois formatos
$senhaValida = false;
$precisaMigrar = false;

if (str_starts_with($usuario['senha'], '$2y$') || str_starts_with($usuario['senha'], '$2b$')) {
    // Senha salva com bcrypt (password_hash) — formato novo
    $senhaValida = password_verify($senha, $usuario['senha']);
} else {
    // Senha salva com SHA-256 — formato legado
    $senhaHash = hash('sha256', $senha);
    $senhaValida = hash_equals($usuario['senha'], $senhaHash);
    $precisaMigrar = $senhaValida; // se bateu, migra para bcrypt agora
}

if (!$senhaValida) {
    header("Location: ../index.php?erro=credenciais_invalidas&email=" . urlencode($email));
    exit;
}

// 5. Migração silenciosa: atualiza SHA-256 → bcrypt no primeiro login bem-sucedido
if ($precisaMigrar) {
    $novoHash = password_hash($senha, PASSWORD_BCRYPT);
    $upd = $pdo->prepare("UPDATE tb_usuarios SET senha = ? WHERE id = ?");
    $upd->execute([$novoHash, $usuario['id']]);
}

// 6. Login OK — regenera sessão para evitar session fixation
session_regenerate_id(true);

$_SESSION['usuario_id']      = $usuario['id'];
$_SESSION['usuario_nome']    = $usuario['nome'];
$_SESSION['usuario_tipo']    = $usuario['tipo_usuario'];
$_SESSION['usuario_email']   = $usuario['email'];
$_SESSION['usuario_empresa'] = $usuario['id_empresa'];

// 7. Redireciona para o sistema
header("Location: ../php/dashboard.php");
exit;