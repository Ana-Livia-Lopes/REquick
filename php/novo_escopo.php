<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requick - Escopo Inicial</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/novo_escopo.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="BarraLateral">
        <div class="LogoTopo">
            <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogo" />
        </div>
        <nav class="MenuNav">
            <a href="#" class="ItemMenu ItemMenuAtivo">
            <i class="fa-solid fa-layer-group"></i>
                Dashboard
            </a>
            <a href="#" class="ItemMenu">
            <i class="fa-solid fa-folder-open" style="color: rgb(255, 255, 255);"></i>
                Projetos
            </a>
        </nav>
        <div class="PerfilUsuario">
            <div class="AvatarPerfil">VK</div>
            <div class="InfoPerfil">
                <p class="NomeUsuario">Victor Koba</p>
                <p class="CargoUsuario">(administrador)</p>
            </div>
        </div>
    </div>
        <div class="div-container-escopo">
            <div class="div-flecha">
                <a href="projeto.php" class="referencia-projeto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="currentColor" d="m12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                    <p>Ir à tela inicial do projeto</p>
                </a>
            </div>
            <div class="div-titulo-subtitulo-escopo">
                <h1 class="titulo-escopo">Escopo Inicial</h1>
                <h3 class="subtitulo-escopo">Centralize aqui as percepções e necessidades coletadas durante as entrevistas com o cliente. Este registro servirá como base estratégica para a estruturação de requisitos e consultas futuras durante todo o ciclo de vida do projeto</h3>
            </div>
            <div id="container-texto">
                <div id="toolbar">
                    <div class="group">
                        <button data-cmd="bold" title="Negrito">
                            <i data-lucide="bold"></i>
                        </button>

                        <button data-cmd="italic" title="Itálico">
                            <i data-lucide="italic"></i>
                        </button>

                        <button data-cmd="strike" title="Riscado">
                            <i data-lucide="strikethrough"></i>
                        </button>
                    </div>

                    <div class="group">
                        <button data-cmd="paragraph" title="Parágrafo">
                            <i data-lucide="pilcrow"></i>
                        </button>

                        <button data-cmd="h1" title="Título 1">
                            H1
                        </button>

                        <button data-cmd="h2" title="Título 2">
                            H2
                        </button>
                    </div>

                    <div class="group">
                        <button data-cmd="bulletList" title="Lista">
                            <i data-lucide="list"></i>
                        </button>

                        <button data-cmd="orderedList" title="Lista numerada">
                            <i data-lucide="list-ordered"></i>
                        </button>
                    </div>
                </div>
            <div id="editor"></div>
        </div>
    <script type="module" src="../js/script.js"></script>
</body>
</html>