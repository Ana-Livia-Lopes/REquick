<?php
require_once '../config/verificar_sessao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Requick - Projetos</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/projetos.css" />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="LayoutPadrao">

    <?php
      require_once 'projeto_acoes.php'; 
      $projetos = new \php\Projeto();
      $projetoDao = new \php\ProjetoDao($projetos);


    ?>

  <?php include 'navbar_lateral.php'; ?>
  <?php $paginaAtiva = 'projetos'; include 'navbar_lateral.php'; ?>

  <div class="AreaRolavel AreaRolavelDashboard">
    <main class="ConteudoPrincipal">

      <header class="CabecalhoPagina">
        <h1 class="TituloBoasVindas">Meus Projetos</h1>
      </header>

      <div class="ContainerBusca">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" class="CampoBusca" placeholder="Buscar projetos..." />
      </div>

      <div class="SecaoProjetos">
        <div class="CabecalhoProjetos">
          <h2 class="TituloSecao">Todos os projetos</h2>
          <label for="CheckboxModal" class="BotaoCadastrar">Cadastrar Projeto +</label>
        </div>

        <div class="GradeProjetos">
          <?php foreach ($projetoDao->read_projetos() as $projeto): ?>
                    <div class="CardProjeto">

                        <h3 class="NomeProjeto">
                            <?= htmlspecialchars($projeto['nome_projeto']) ?>
                        </h3>

                        <p class="DescricaoProjeto">
                            <?= !empty($projeto['descricao']) 
                                ? htmlspecialchars($projeto['descricao']) 
                                : 'Sem descrição disponível' ?>
                        </p>

                        <p class="AutorAlteracao">
                            <?php if (!empty($projeto['autor'])): ?>

                                Última alteração feita por 
                                <strong>
                                    <?= htmlspecialchars($projeto['autor']) ?>
                                </strong>

                                <br>

                                <small>
                                    <?= htmlspecialchars($projeto['modificacao']) ?>
                                </small>

                                <br>

                                <small>
                                    <?= date('d/m/Y H:i', strtotime($projeto['data_modificacao'])) ?>
                                </small>

                            <?php else: ?>

                                <em>Nenhuma alteração registrada</em>

                            <?php endif; ?>
                        </p>

                        <a href="php/projeto.php?id=<?= $projeto['id'] ?>" class="BotaoAcessar">
                            Acessar projeto
                        </a>

                    </div>
                <?php endforeach; ?>
        </div>
      </div>
    </main>
  </div>

  <input type="checkbox" id="CheckboxModal" class="CheckboxModal" />
  <div class="FundoModal">
    <label for="CheckboxModal" class="SombreaModal"></label>
    <div class="ContainerModal">
      <label for="CheckboxModal" class="BotaoVoltar">
        <i class="fa-solid fa-angle-left"></i> Voltar
      </label>
      <div class="LogoModal">
        <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogoModal" />
      </div>
      <h2 class="TituloModal">Novo Projeto</h2>
      <div class="GrupoFormulario">
        <label class="LabelFormulario" for="InputNomeProjeto">Nome do projeto</label>
        <input type="text" id="InputNomeProjeto" class="CampoFormulario" placeholder="Digite o nome do projeto" />
      </div>
      <div class="GrupoFormulario">
        <label class="LabelFormulario" for="InputDescricao">Descrição</label>
        <textarea id="InputDescricao" class="CampoFormulario CampoTextarea" placeholder="Digite a descrição do projeto"></textarea>
      </div>
      <div class="GrupoFormulario">
        <label class="LabelFormulario" for="InputPrevisao">Previsão de entrega</label>
        <input type="date" id="InputPrevisao" class="CampoFormulario" />
      </div>
      <div class="CentralizarDiv2">
        <a href="./novo_escopo.php"><button class="BotaoCriar">Criar Projeto</button></a>
      </div>
    </div>
  </div>
</body>
</html>
