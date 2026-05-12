<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick – Dashboard</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/projetos.css" />
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php
        require_once 'php/projeto_acoes.php'; 
        $projetos = new \php\Projeto();
        $projetoDao = new \php\ProjetoDao($projetos);


    ?>
    <aside class="BarraLateral">
        <div class="LogoTopo">
            <img src="img/logo-requick.png" alt="Requick" class="ImagemLogo" />
        </div>

        <nav class="MenuNav">
            <a href="index.php" class="ItemMenu ItemMenuAtivo">
                <i class="fa-solid fa-layer-group"></i>
                Dashboard
            </a>
            <a href="php/projetos.php" class="ItemMenu">
                <i class="fa-solid fa-folder-open"></i>
                Projetos
            </a>
            <a href="php/configuracoes.php" class="ItemMenu">
                <i class="fa-solid fa-gear"></i>
                Configurações
            </a>
        </nav>

        <a href="./php/perfil.php" class="LinkPerfil">
        <div class="PerfilUsuario <?= $paginaAtiva === 'perfil' ? 'PerfilAtivo' : '' ?>">
            <div class="AvatarPerfil">VK</div>
            <div class="InfoPerfil">
                <p class="NomeUsuario">Victor Koba</p>
                <p class="CargoUsuario">(administrador)</p>
            </div>
        </div>
        </a>
    </aside>

    <div class="ConteudoPrincipal">

        <header class="CabecalhoPagina">
            <h1 class="TituloBoasVindas">Olá Victor, bem-vindo(a) ao Dashboard!</h1>
        </header>

        <div class="ContainerBusca">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" class="CampoBusca" placeholder="Buscar projetos, requisitos ou tags..." />
        </div>

        <?php 
        $projetos = $projetoDao->countStatus();
        $requisitos = $projetoDao->countRequisitos();
        ?>

        <div class="GradeEstatisticas">
            <div class="CardEstatistica CardEstatisticaEscuro">
                <div class="InfoCard">
                    <p class="LabelCard">Projetos ativos</p>
                    <p class="NumeroCard"><?= $projetos['ativos'] ?></p>
                    <p class="SubtituloCard">Em produção</p>
                </div>
                <div class="IconeCard"><i class="fa-regular fa-folder"></i></div>
            </div>

            <div class="CardEstatistica CardEstatisticaEscuro">
                <div class="InfoCard">
                    <p class="LabelCard">Requisitos pendentes</p>
                    <p class="NumeroCard"><?= $requisitos['desativados'] ?></p>
                    <p class="SubtituloCard">Aguardando cliente</p>
                </div>
                <div class="IconeCard"><i class="fa-regular fa-clock"></i></div>
            </div>

            <div class="CardEstatistica CardEstatisticaEscuro">
                <div class="InfoCard">
                    <p class="LabelCard">Projetos concluídos</p>
                    <p class="NumeroCard"><?= $projetos['desativados'] ?></p>
                    <p class="SubtituloCard">Finalizado</p>
                </div>
                <div class="IconeCard"><i class="fa-regular fa-circle-check"></i></div>
            </div>
        </div>

        <div class="SecaoProjetos">
            <div class="CabecalhoProjetos">
                <h2 class="TituloSecao">Projeto(s) recente(s)</h2>
                <label for="CheckboxModal" class="BotaoCadastrar">Cadastrar Projeto +</label>
            </div>
            <div class="GradeProjetos">
                <?php foreach ($projetoDao->read() as $projeto): ?>
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
                <a href="./php/projetos.php" class="BotaoProjetos">Ver todos Projetos</label></a>

            </div>
            
        </div>

        <div class="FeedAtividades">
            <h2 class="TituloFeed">Feed de Atividades</h2>
            <ul class="ListaAtividades">
                <li class="ItemAtividade">
                    <div class="AvatarAtividade">VK</div>
                    <div class="ConteudoAtividade">
                        <div class="LinhaAtividade">
                            <p class="NomeAtividade">Victor Koba</p>
                            <p class="TempoAtividade">2 min atrás</p>
                        </div>
                        <p class="TextoAtividade">editou Requisito RF01 no E-commerce Alpha</p>
                    </div>
                </li>
                <li class="ItemAtividade">
                    <div class="AvatarAtividade">JP</div>
                    <div class="ConteudoAtividade">
                        <div class="LinhaAtividade">
                            <p class="NomeAtividade">João Pedro</p>
                            <p class="TempoAtividade">3 horas atrás</p>
                        </div>
                        <p class="TextoAtividade">editou Requisito RF02 no Projeto Beta</p>
                    </div>
                </li>
                <li class="ItemAtividade">
                    <div class="AvatarAtividade">JA</div>
                    <div class="ConteudoAtividade">
                        <div class="LinhaAtividade">
                            <p class="NomeAtividade">Jacquys</p>
                            <p class="TempoAtividade">5 horas atrás</p>
                        </div>
                        <p class="TextoAtividade">editou Requisito RNF15 no Projeto Abençoado</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <input type="checkbox" id="CheckboxModal" class="CheckboxModal" />
  <div class="FundoModal">
    <label for="CheckboxModal" class="SombreaModal"></label>
    <div class="ContainerModal">
      <label for="CheckboxModal" class="BotaoVoltar">
        <i class="fa-solid fa-angle-left"></i> Voltar
      </label>
      <div class="LogoModal">
        <img src="./img/logo-requick.png" alt="Requick" class="ImagemLogoModal" />
      </div>
      <h2 class="TituloModal">Novo Projeto</h2>
      <form action="">
        
          <div class="GrupoFormulario">
            <label class="LabelFormulario" for="InputNomeProjeto">Nome do projeto</label>
            <input type="text" name="InputNomeProjeto" id="InputNomeProjeto" class="CampoFormulario" placeholder="Digite o nome do projeto" />
          </div>
          <div class="GrupoFormulario">
            <label class="LabelFormulario" for="InputDescricao">Descrição</label>
            <textarea id="InputDescricao" name="InputDescricao" class="CampoFormulario CampoTextarea" placeholder="Digite a descrição do projeto"></textarea>
          </div>
          <div class="GrupoFormulario">
            <label class="LabelFormulario" for="InputPrevisao">Previsão de entrega</label>
            <input type="date" name="InputPrevisao" id="InputPrevisao" class="CampoFormulario" />
          </div>
          <div class="GrupoFormulario">
            <label class="LabelFormulario" for="selectEmpresa">Empresa</label>
            <select type="date" name="selectEmpresa" id="selectEmpresa" class="CampoFormulario" >
                <?php foreach ($projetoDao->read() as $projeto): ?>

                    <option value="<?= htmlspecialchars($projeto['id']) ?>">
                        <?= htmlspecialchars($projeto['nome_projeto']) ?>
                    </option>

                <?php endforeach; ?>
            </select>
          </div>
          <div class="CentralizarDiv2">
           <button type="submit" class="BotaoCriar">Criar Projeto</button> 
          </div>

      </form>
    </div>
  </div>
</body>
</html>
