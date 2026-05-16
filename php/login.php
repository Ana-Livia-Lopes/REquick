<?php
session_start();

require_once __DIR__ . '/conexao.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if (empty($email) || empty($senha)) {
    header('Location: ../index.php?erro=campos_vazios');
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, nome, email, tipo_usuario, especializacao, senha, id_empresa
     FROM tb_usuarios
     WHERE email = ?
     LIMIT 1"
);

$stmt->bind_param('s', $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header('Location: ../index.php?erro=credenciais_invalidas');
    exit;
}

$usuario = $resultado->fetch_assoc();
$stmt->close();



$senhaHash = hash('sha256', $senha);

if (!hash_equals($usuario['senha'], $senhaHash)) {
    header('Location: ../index.php?erro=credenciais_invalidas');
    exit;
}

$_SESSION['usuario_id']          = $usuario['id'];
$_SESSION['usuario_nome']        = $usuario['nome'];
$_SESSION['usuario_email']       = $usuario['email'];
$_SESSION['usuario_tipo']        = $usuario['tipo_usuario'];
$_SESSION['usuario_especializacao'] = $usuario['especializacao'];
$_SESSION['usuario_empresa']     = $usuario['id_empresa'];

$conn->close();

header('Location: dashboard.php');
exit;
?>