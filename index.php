<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick – Login</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/acesso.css" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <div class="CardLogin">
        <img src="img/logo-requick.png" alt="Requick" class="ImagemLogoLogin">
        <h2>Entrar na sua conta</h2>

        <?php
        // Exibe mensagem de erro vinda da query string
        if (!empty($_GET['erro'])):
            $mensagens = [
                'credenciais_invalidas' => 'E-mail ou senha incorretos.',
                'campos_vazios'         => 'Preencha todos os campos.',
            ];
            $msg = $mensagens[$_GET['erro']] ?? 'Ocorreu um erro. Tente novamente.';
        ?>
            <p class="MensagemErro">
                <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($msg) ?>
            </p>
        <?php endif; ?>


        <form action="php/login.php" method="POST">
            <label for="IdEmail">Email</label>
            <input type="email" id="IdEmail" name="email"
                placeholder="Digite o seu email"
                value="<?= htmlspecialchars($_GET['email'] ?? '') ?>"
                required>


            <label for="IdSenha">Senha</label>
            <input type="password" id="IdSenha" name="senha"
                placeholder="Digite a sua senha"
                required>


            <a href=""><small>Esqueceu a senha?</small></a>
            <button type="submit" class="BotaoEntrar">Entrar</button>
        </form>
    </div>
</body>

</html>