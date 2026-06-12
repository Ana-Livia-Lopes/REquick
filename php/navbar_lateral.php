<?php
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Usuário';
$tipoUsuario = $_SESSION['usuario_tipo'] ?? 'Visitante';
?>
<aside class="BarraLateral" id="barraLateral">
    <div class="BarraLateralTopo">
        <button class="MenuToggle" type="button" aria-label="Abrir menu" aria-expanded="false" aria-controls="menuLateral">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="LogoTopo">
            <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogo" />
        </div>
    </div>

    <div class="BarraLateralConteudo" id="menuLateral">
        <nav class="MenuNav">
            <a href="dashboard.php" class="ItemMenu <?= $paginaAtiva === 'dashboard' ? 'ItemMenuAtivo' : '' ?>">
                <i class="fa-solid fa-layer-group"></i>
                <span>Dashboard</span>
            </a>
            <a href="projetos.php" class="ItemMenu <?= $paginaAtiva === 'projetos' ? 'ItemMenuAtivo' : '' ?>">
                <i class="fa-solid fa-folder-open"></i>
                <span>Projetos</span>
            </a>

            <?php if ($tipoUsuario === 'Administrador'): ?>
                <a href="cadastro.php" class="ItemMenu <?= $paginaAtiva === 'cadastro' ? 'ItemMenuAtivo' : '' ?>">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Cadastro</span>
                </a>
            <?php endif; ?>

            <a href="logout.php" class="ItemMenu">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
            </a>
        </nav>

        <a href="perfil.php" class="LinkPerfil">
            <div class="PerfilUsuario <?= $paginaAtiva === 'perfil' ? 'PerfilAtivo' : '' ?>">
                <div class="AvatarPerfil">
                    <?= strtoupper(substr($nomeUsuario, 0, 1)) ?>
                </div>

                <div class="InfoPerfil">
                    <p class="NomeUsuario"><?= htmlspecialchars($nomeUsuario) ?></p>
                    <p class="CargoUsuario">(<?= htmlspecialchars($tipoUsuario) ?>)</p>
                </div>
            </div>
        </a>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const barra = document.getElementById('barraLateral');
        const botao = barra ? barra.querySelector('.MenuToggle') : null;

        if (!barra || !botao) return;

        botao.addEventListener('click', function () {
            const aberto = barra.classList.toggle('is-open');
            botao.setAttribute('aria-expanded', aberto ? 'true' : 'false');
        });

        barra.querySelectorAll('.ItemMenu, .LinkPerfil').forEach(function (item) {
            item.addEventListener('click', function () {
                if (window.innerWidth <= 900) {
                    barra.classList.remove('is-open');
                    botao.setAttribute('aria-expanded', 'false');
                }
            });
        });
    });
</script>