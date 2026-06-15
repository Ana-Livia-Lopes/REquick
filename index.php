<?php
// Sessão sem cookie persistente: expira ao fechar o navegador
session_set_cookie_params([
    'lifetime' => 0,        // 0 = cookie de sessão (some ao fechar o navegador)
    'path' => '/',
    'secure' => false,    // mude para true em produção com HTTPS
    'httponly' => true,     // impede acesso via JavaScript
    'samesite' => 'Strict',
]);
session_start();

// Se já está logado, redireciona para o sistema
if (!empty($_SESSION['usuario_id'])) {
    header("Location: php/dashboard.php"); // ajuste para sua página principal
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick – Login</title>
    <link rel="stylesheet" href="./css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="./js/login.js"></script>
</head>

<body>

    <!-- Background Shapes -->
    <div class="bg-shape shape-left"></div>
    <div class="bg-shape shape-right"></div>

    <!-- Floating Elements -->
    <div class="floating-card left-card">
        <div class="avatar"></div>
        <div class="lines">
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="floating-card right-card">
        <div class="avatar green"></div>
        <div class="lines">
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="floating-chat">
        <i class="fa-solid fa-comment-dots"></i>
    </div>

    <div class="floating-bolt">
        <i class="fa-solid fa-bolt"></i>
    </div>

    <div class="dots dots-left"></div>
    <div class="dots dots-right"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo">
                <img src="img/logo-requick-azul.png" alt="Requick" class="ImagemLogoLogin">
            </div>
            <h2>Entrar na sua conta</h2>
            <p class="subtitle">
                Colabore em requisitos.
                <span>Em tempo real.</span>
            </p>

            <?php
            // Exibe mensagem de erro vinda da query string
            if (!empty($_GET['erro'])):
                $mensagens = [
                    'credenciais_invalidas' => 'E-mail ou senha incorretos.',
                    'campos_vazios' => 'Preencha todos os campos.',
                ];
                $msg = $mensagens[$_GET['erro']] ?? 'Ocorreu um erro. Tente novamente.';
                ?>
                <p class="MensagemErro">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($msg) ?>
                </p>
            <?php endif; ?>

            <form id="loginForm" action="php/login.php" method="POST">
                <div class="form-group">
                    <label for="IdEmail">Email</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" id="IdEmail" name="email" placeholder="Digite o seu email"
                            value="<?= htmlspecialchars($_GET['email'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="IdSenha">Senha</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="IdSenha" name="senha" placeholder="Digite a sua senha" required>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-login">
                    Entrar
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
            <div class="divider">
                <span>ou continue com</span>
            </div>

            <div class="social-login">
                <a href="./php/google-login.php" class="social-btn">
                    <img src="https://www.google.com/favicon.ico" alt="">
                    Google
                </a>
            </div>
        </div>
    </div>
</body>

</html>