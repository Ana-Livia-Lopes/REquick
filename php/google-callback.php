<?php
session_start();
require '../vendor/autoload.php';

use League\OAuth2\Client\Provider\Google;

// 1. Conexão com o seu banco de dados MySQL
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'bd_requick';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Erro de conexão com o banco: ' . $e->getMessage());
}

// Validação de Segurança (CSRF)
if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Estado inválido.');
}

$provider = new Google([
    'clientId'     => '1088845469512-ibi4qp9l8tdgk0jb62q4en4q6iiimuh1.apps.googleusercontent.com',
    'clientSecret' => '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',
    'redirectUri'  => 'http://localhost/requick/REquick/php/google-callback.php'
]);

try {
    $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
    $user = $provider->getResourceOwner($token);
    
    $emailDoUsuario = $user->getEmail();

    // 2. VERIFICAÇÃO NO MYSQL: O e-mail está autorizado?
    $stmt = $pdo->prepare("
    SELECT id, nome
    FROM tb_usuarios
    WHERE email = :email
    LIMIT 1
    ");

    $stmt->execute(['email' => $emailDoUsuario]);
    $usuarioAutorizado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuarioAutorizado) {
    session_destroy();
    exit("Usuário não cadastrado no sistema.");
    }

    if ($usuarioAutorizado['status'] !== 'ativo') {
        session_destroy();
        exit("Usuário inativo. Entre em contato com o administrador.");
    }

    // 3. Se passou pela verificação, cria a sessão normalmente
    $_SESSION['user'] = [
    'id'            => $usuarioAutorizado['id'],
    'google_id'     => $user->getId(),
    'nome'          => $usuarioAutorizado['nome'],
    'email'         => $emailDoUsuario,
    'tipo_usuario'  => $usuarioAutorizado['tipo_usuario'],
    'avatar'        => $user->getAvatar()
    ];

    unset($_SESSION['oauth2state']);
    header("Location: ./php/dashboard.php");
    exit;

} catch (\Exception $e) {
    exit('Falha ao obter dados: ' . $e->getMessage());
}
