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

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if (empty($email) || empty($senha)) {
    header("Location: ../index.php?erro=campos_vazios");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tb_usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: ../index.php?erro=credenciais_invalidas&email=" . urlencode($email));
    exit;
}

$senhaValida = false;
$precisaMigrar = false;

if (str_starts_with($usuario['senha'], '$2y$') || str_starts_with($usuario['senha'], '$2b$')) {
    $senhaValida = password_verify($senha, $usuario['senha']);
} else {
    $senhaHash = hash('sha256', $senha);
    $senhaValida = hash_equals($usuario['senha'], $senhaHash);
    $precisaMigrar = $senhaValida;
}

if (!$senhaValida) {
    header("Location: ../index.php?erro=credenciais_invalidas&email=" . urlencode($email));
    exit;
}

if ($precisaMigrar) {
    $novoHash = password_hash($senha, PASSWORD_BCRYPT);
    $upd = $pdo->prepare("UPDATE tb_usuarios SET senha = ? WHERE id = ?");
    $upd->execute([$novoHash, $usuario['id']]);
}

session_regenerate_id(true);

$_SESSION['usuario_id']      = $usuario['id'];
$_SESSION['usuario_nome']    = $usuario['nome'];
$_SESSION['usuario_tipo']    = $usuario['tipo_usuario'];
$_SESSION['usuario_email']   = $usuario['email'];
$_SESSION['usuario_empresa'] = $usuario['id_empresa'];

header("Location: ../php/dashboard.php");
exit;