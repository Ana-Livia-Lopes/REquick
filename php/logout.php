<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,    // mude para true em produção com HTTPS
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

// Limpa todas as variáveis da sessão
$_SESSION = [];

// Apaga o cookie de sessão do navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destrói a sessão no servidor
session_destroy();

// Evita cache de páginas protegidas após logout
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// Redireciona para o login de forma robusta
$redirect = dirname($_SERVER['SCRIPT_NAME']) . '/../index.php';
header("Location: $redirect");
exit;