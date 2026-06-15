<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,  
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

if (empty($_SESSION['usuario_id'])) {
    $redirect = dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/index.php';
    header("Location: $redirect");
    exit;
}