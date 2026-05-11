<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick - Configurações</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php $paginaAtiva = 'configuracoes'; include 'navbar_lateral.php'; ?>

    <div class="ConteudoPrincipal">
        <header class="CabecalhoPagina">
            <h1 class="TituloBoasVindas">Configurações</h1>
        </header>

        <section class="CardConfig">
            <h3>Preferências</h3>
            <div class="LinhaConfig">
                <div>
                    <strong>Notificações por e-mail</strong>
                    <p>Receber atualizações sobre projetos e requisitos.</p>
                </div>
                <label class="Switch">
                    <input type="checkbox" checked>
                    <span class="Slider"></span>
                </label>
            </div>
            <div class="LinhaConfig">
                <div>
                    <strong>Resumo semanal</strong>
                    <p>Receber um resumo das atividades da equipe toda segunda.</p>
                </div>
                <label class="Switch">
                    <input type="checkbox">
                    <span class="Slider"></span>
                </label>
            </div>
            <div class="LinhaConfig">
                <div>
                    <strong>Modo compacto</strong>
                    <p>Reduzir espaçamentos das listas de requisitos.</p>
                </div>
                <label class="Switch">
                    <input type="checkbox">
                    <span class="Slider"></span>
                </label>
            </div>
        </section>

        <section class="CardConfig">
            <h3>Segurança</h3>
            <form action="alterar_senha.php" method="POST" class="FormConfig">
                <label>Senha atual<input type="password" name="senha_atual" placeholder="••••••••"></label>
                <label>Nova senha<input type="password" name="nova_senha" placeholder="••••••••"></label>
                <label>Confirmar nova senha<input type="password" name="confirmar_senha" placeholder="••••••••"></label>
                <button type="submit" class="BotaoSalvarConfig">Salvar alterações</button>
            </form>
        </section>
    </div>
</body>
</html>
