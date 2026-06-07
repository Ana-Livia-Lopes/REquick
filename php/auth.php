<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,    // mude para true em produção com HTTPS
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

if (empty($_SESSION['usuario_id'])) {
    header("Location: index.php"); // ajuste o caminho se necessário
    exit;
}