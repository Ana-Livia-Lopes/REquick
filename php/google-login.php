<?php
// 1. Sempre inicie a sessão antes de usar $_SESSION
session_start(); 

require '../vendor/autoload.php';

// 2. Corrigido para o namespace oficial da PHP League
use League\OAuth2\Client\Provider\Google; 

$provider = new Google([
    'clientId'     => '1088845469512-ibi4qp9l8tdgk0jb62q4en4q6iiimuh1.apps.googleusercontent.com',
    'clientSecret' => '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',
    'redirectUri'  => 'http://localhost/requick/REquick/php/google-callback.php'
]);

// Gera a URL de redirecionamento para o Google
$authUrl = $provider->getAuthorizationUrl();

// Salva o estado atual para validar contra ataques CSRF no callback
$_SESSION['oauth2state'] = $provider->getState();

// Redireciona o usuário
header('Location: ' . $authUrl);
exit;
