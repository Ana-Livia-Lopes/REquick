<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick - Perfil</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php $paginaAtiva = 'perfil'; include 'navbar_lateral.php'; ?>

    <div class="ConteudoPrincipal">
        <header class="CabecalhoPagina">
            <h1 class="TituloBoasVindas">Meu Perfil</h1>
        </header>

        <section class="CardPerfil">
            <div class="CabecalhoPerfil">
                <div class="AvatarPerfilGrande">VK</div>
                <div class="InfoPerfilGrande">
                    <h2>Victor Koba</h2>
                    <p>Administrador</p>
                    <span><i class="fa-solid fa-envelope"></i> victor.koba@requick.com</span>
                </div>
                <a href="configuracoes.php" class="BotaoCadastrar">Editar perfil</a>
            </div>

            <div class="GradeEstatisticasPerfil">
                <div class="CardEstatistica CardEstatisticaEscuro">
                    <div class="InfoCard">
                        <p class="LabelCard">Projetos ativos</p>
                        <p class="NumeroCard">15</p>
                    </div>
                    <div class="IconeCard"><i class="fa-regular fa-folder"></i></div>
                </div>
                <div class="CardEstatistica CardEstatisticaEscuro">
                    <div class="InfoCard">
                        <p class="LabelCard">Requisitos criados</p>
                        <p class="NumeroCard">42</p>
                    </div>
                    <div class="IconeCard"><i class="fa-regular fa-file-lines"></i></div>
                </div>
                <div class="CardEstatistica CardEstatisticaEscuro">
                    <div class="InfoCard">
                        <p class="LabelCard">Equipes</p>
                        <p class="NumeroCard">3</p>
                    </div>
                    <div class="IconeCard"><i class="fa-regular fa-user"></i></div>
                </div>
            </div>

            <div class="BlocoDadosPerfil">
                <h3>Dados da conta</h3>
                <ul>
                    <li><span>Nome completo</span><strong>Victor Koba</strong></li>
                    <li><span>E-mail</span><strong>victor.koba@requick.com</strong></li>
                    <li><span>Cargo</span><strong>Administrador</strong></li>
                    <li><span>Equipe</span><strong>Núcleo Requick</strong></li>
                    <li><span>Conta criada em</span><strong>10/04/2026</strong></li>
                </ul>
            </div>
        </section>
    </div>
</body>
</html>
