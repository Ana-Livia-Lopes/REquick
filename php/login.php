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

// conexao.php retorna $pdo diretamente
$pdo = require_once __DIR__ . '/../config/conexao.php';

// 1. Coleta e sanitiza
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// 2. Valida campos vazios
if (empty($email) || empty($senha)) {
    header("Location: ../index.php?erro=campos_vazios");
    exit;
}

// 3. Gera SHA-256 da senha digitada
$senhaHash = hash('sha256', $senha);

// 4. Busca usuário pelo e-mail
$stmt = $pdo->prepare("SELECT * FROM tb_usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// 5. Compara hashes
if (!$usuario || !hash_equals($usuario['senha'], $senhaHash)) {
    header("Location: ../index.php?erro=credenciais_invalidas&email=" . urlencode($email));
    exit;
}

// 6. Login OK — regenera sessão para evitar session fixation
session_regenerate_id(true);

$_SESSION['usuario_id']      = $usuario['id'];
$_SESSION['usuario_nome']    = $usuario['nome'];
$_SESSION['usuario_tipo']    = $usuario['tipo_usuario'];
$_SESSION['usuario_email']   = $usuario['email'];
$_SESSION['usuario_empresa'] = $usuario['id_empresa']; // usado para filtrar projetos/requisitos

// 7. Redireciona para o sistema
header("Location: ../php/dashboard.php");
exit;