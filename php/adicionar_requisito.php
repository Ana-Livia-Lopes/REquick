<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requick - Adicionar Requisito</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <?php $paginaAtiva = 'projetos'; include 'navbar_lateral.php'; ?>

    <div class="ConteudoPrincipal">
        <div class="div-container-escopo">
            <div class="div-flecha">
                <a href="projeto.php" class="referencia-projeto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="currentColor" d="M20 11H7.83l5.59-5.59L12 4l-8 8l8 8l1.41-1.41L7.83 13H20z"/></svg>
                    <p>Voltar à tela inicial do projeto</p>
                </a>
            </div>
            <div class="div-titulo-subtitulo-escopo">
                <h1 class="titulo-escopo">Novo Requisito</h1>
            </div>
            <form action="processa_adicionar_requisito.php" method="POST">
                <div id="container-adicionar-requisito">
                    <select name="tipo_requisito" id="">
                        <option value="" disabled selected>Selecione o tipo de requisito</option>
                        <option value="funcional">Requisito Funcional - RF</option>
                        <option value="nao_funcional">Requisito Não Funcional - RNF</option>
                    </select>
                    <div class="linha"></div>
                    <div class="titulo-requisito">
                        <input name="titulo_requisito" type="text" placeholder="Insira o título">
                    </div>
                    <div class="descricao-requisito">
                        <textarea name="descricao_requisito" id="" placeholder="Descrição do requisito"></textarea>
                    </div>
                </div>
                <div class="botao-adicionar-requisito">
                    <button type="submit">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
