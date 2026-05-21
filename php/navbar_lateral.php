<?php
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Usuário';
$tipoUsuario = $_SESSION['usuario_tipo'] ?? 'Visitante';
?>
<aside class="BarraLateral">
    <div class="LogoTopo">
        <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogo" />
    </div>

    <nav class="MenuNav">
        <a href="dashboard.php" class="ItemMenu <?= $paginaAtiva === 'dashboard' ? 'ItemMenuAtivo' : '' ?>">
            <i class="fa-solid fa-layer-group"></i>
            Dashboard
        </a>
        <a href="projetos.php" class="ItemMenu <?= $paginaAtiva === 'projetos' ? 'ItemMenuAtivo' : '' ?>">
            <i class="fa-solid fa-folder-open"></i>
            Projetos
        </a>
        <a href="configuracoes.php" class="ItemMenu <?= $paginaAtiva === 'configuracoes' ? 'ItemMenuAtivo' : '' ?>">
            <i class="fa-solid fa-gear"></i>
            Configurações
        </a>
    </nav>

    <!-- Div de Perfil transformada em link -->

    <a href="perfil.php" class="LinkPerfil">
        <div class="PerfilUsuario <?= $paginaAtiva === 'perfil' ? 'PerfilAtivo' : '' ?>">

            <div class="AvatarPerfil">
                <?= strtoupper(substr($nomeUsuario, 0, 1)) ?>
            </div>

            <div class="InfoPerfil">
                <p class="NomeUsuario">
                    <?= htmlspecialchars($nomeUsuario) ?>
                </p>

                <p class="CargoUsuario">
                    (<?= htmlspecialchars($tipoUsuario) ?>)
                </p>
            </div>

        </div>
    </a>
</aside>