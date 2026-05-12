<?php if (!isset($paginaAtiva)) { $paginaAtiva = ''; } ?>
<aside class="BarraLateral">
    <div class="LogoTopo">
        <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogo" />
    </div>

    <nav class="MenuNav">
        <a href="../index.php" class="ItemMenu ">
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
            <div class="AvatarPerfil">VK</div>
            <div class="InfoPerfil">
                <p class="NomeUsuario">Victor Koba</p>
                <p class="CargoUsuario">(administrador)</p>
            </div>
        </div>
    </a>
</aside>


