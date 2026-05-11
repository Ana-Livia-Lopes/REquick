<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Requick – Projetos</title>
  <link rel="stylesheet" href="../css/projeto.css" />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&display=swap" rel="stylesheet" />
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

  <div class="BarraLateral">

    <div class="LogoTopo">
      <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogo" />
    </div>

    <nav class="MenuNav">
      <a href="../index.html" class="ItemMenu">
        <i class="fa-solid fa-layer-group" style="color: rgb(255, 255, 255);"></i>
        Dashboard
      </a>
      <a href="projeto.php" class="ItemMenu ItemMenuAtivo">
        <i class="fa-solid fa-folder-open"></i>
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

  <div class="ConteudoPrincipal">

    <header class="CabecalhoPagina">
      <h1 class="TituloBoasVindas">< Voltar para projetos</h1>
      <div id="exportar-projeto" class="btn-header">
        <h4>Exportar projeto</h4>
        <i class="fa-solid fa-file-export" style="color: rgb(255, 255, 255);"></i>
      </div>
      <div id="visualizar-escopo" class="btn-header">
        <h4>Visualizar escopo inicial</h4>
        <i class="fa-solid fa-file-lines" style="color: rgb(255, 255, 255);"></i>
      </div>
      <div id="convidar-usuarios">
        <h4>Convidar usuários</h4>
        <i class="fa-solid fa-user-plus"></i>
      </div>
    </header>

    <h1>E-commerce Alpha</h1>

    <div class="bloco-comentarios">
    
        <div class="titulo-sessao">
            Comentários e/ou solicitações de mudanças
        </div>

        <div class="linha-comentario">
            <p><strong>Solicitação de mudança:</strong> Preciso que revise o requisito RF02 e altere para o que foi pedido no escopo inicial</p>
            <span>André - Cliente</span>
        </div>

        <div class="linha-comentario">
            <p><strong>Comentário:</strong> É necessário a finalização do requisito RF01, ler o escopo inicial e fazer a alteração do que foi pedido</p>
            <span>João - Responsável Projeto</span>
        </div>

        <div class="seta-container">
            <div class="setinha"></div>
        </div>

    </div>

    <div id="busca-e-add-requisito">
    <div class="ContainerBusca">
      <i class="fa-solid fa-magnifying-glass" style="color: rgba(54, 78, 118, 0.792);"></i>
      <input type="text" class="CampoBusca" placeholder="Buscar requisitos, tags, ou responsáveis neste projeto..." />
    </div>

    <a href="adicionar_requisito.php" class="BotaoCadastrar">Adicionar requisito +</a>

    </div>

    <!-- <h2 class="TituloSecao">Projeto(s) recente(s)</h2> -->

    <!-- <div class="CabecalhoProjetos">
        
      </div> -->

    <div class="container-requisitos">
        <div class="tabela-header">
            <div class="col-titulo">Título</div>
            <div class="col-status">Status</div>
            <div class="col-prioridade">Prioridade</div>
            <div class="col-responsavel">Responsável</div>
            <div class="col-acoes">Ações</div>
        </div>

        <div class="tabela-linha">
            <div class="col-titulo">
                <strong>RF01 - Disponibilidade</strong>
                <span>última vez alterado(a) por Victor Koba</span>
            </div>
            <div class="col-status">
                <span class="badge yellow">Em Andamento</span>
            </div>
            <div class="col-prioridade">Média</div>
            <div class="col-responsavel">Victor Koba</div>
            <div class="col-acoes">
                <button class="btn-excluir">Excluir</button>
                <button class="btn-editar">Editar</button>
            </div>
        </div>

        <div class="tabela-linha">
            <div class="col-titulo">
                <strong>RF02 - Gestão de usuários</strong>
                <span>última vez alterado(a) por Victor Koba</span>
            </div>
            <div class="col-status">
                <span class="badge green">Validado</span>
            </div>
            <div class="col-prioridade">--</div>
            <div class="col-responsavel">Jacquys</div>
            <div class="col-acoes">
                <button class="btn-excluir">Excluir</button>
                <button class="btn-editar">Editar</button>
            </div>
        </div>

        <div class="tabela-linha">
            <div class="col-titulo">
                <strong>RF03 - Edição de perfil</strong>
                <span>última vez alterado(a) por Ana Lívia</span>
            </div>
            <div class="col-status">
                <span class="badge green">Validado</span>
            </div>
            <div class="col-prioridade">--</div>
            <div class="col-responsavel">João Pedro</div>
            <div class="col-acoes">
                <button class="btn-excluir">Excluir</button>
                <button class="btn-editar">Editar</button>
            </div>
        </div>

        <div class="tabela-footer">
            <div class="seta-v"></div>
        </div>
    </div>

  </div>
<div class="CentralizarDiv">

    <div id="info-projeto">
        <h3>Informações do Projeto</h3>
        <h4>Progresso:</h4>
        <div id="barra">
            <div id="progresso"></div>
        </div>
        <h5>50% concluído</h5>
        <h5 id="data-inicio">Data de início: <span>10/04/2026</span></h5>
        <h5>Data de entrega: <span>30/06/2026</span></h5>
        <h5 id="equipe">Equipe: <span>6 membros</span></h5>
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

      <li class="ItemAtividade">
        <div class="AvatarAtividade">JH</div>
        <div class="ConteudoAtividade">
          <div class="LinhaAtividade">
            <p class="NomeAtividade">Jhônatas</p>
            <p class="TempoAtividade">6 horas atrás</p>
          </div>
          <p class="TextoAtividade">editou Requisito RF01 no E-commerce Alpha</p>
        </div>
      </li>

      <li class="ItemAtividade">
        <div class="AvatarAtividade">AL</div>
        <div class="ConteudoAtividade">
          <div class="LinhaAtividade">
            <p class="NomeAtividade">Ana Lívia</p>
            <p class="TempoAtividade">8 horas atrás</p>
          </div>
          <p class="TextoAtividade">editou Requisito RF02 no Projeto Beta</p>
        </div>
      </li>

      <li class="ItemAtividade">
        <div class="AvatarAtividade">VI</div>
        <div class="ConteudoAtividade">
          <div class="LinhaAtividade">
            <p class="NomeAtividade">Vitório</p>
            <p class="TempoAtividade">9 horas atrás</p>
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
        <i class="fa-solid fa-angle-left" style="color: rgb(83, 102, 133);"></i>
        Voltar
      </label>

      <div class="LogoModal">
        <img src="img/logo-requick.png" alt="Requick" class="ImagemLogoModal" />
      </div>

      <h2 class="TituloModal">Novo Projeto</h2>

      <div class="GrupoFormulario">
        <label class="LabelFormulario" for="InputNomeProjeto">Nome do projeto</label>
        <input type="text" id="InputNomeProjeto" class="CampoFormulario" placeholder="Digite o nome do projeto" />
      </div>

      <div class="GrupoFormulario">
        <label class="LabelFormulario" for="InputDescricao">Descrição</label>
        <textarea id="InputDescricao" class="CampoFormulario CampoTextarea"
          placeholder="Digite a descrição do projeto"></textarea>
      </div>

      <div class="GrupoFormulario">
        <label class="LabelFormulario" for="InputPrevisao">Previsão de entrega</label>
        <input type="date" id="InputPrevisao" class="CampoFormulario" placeholder="dd/mm/aaaa" />
      </div>

      <div class="GrupoFormulario">
        <label class="LabelFormulario" for="InputModelo">Modelo de documentação</label>
        <div class="ContainerSelect">
          <select id="InputModelo" class="CampoSelect">
            <option>Documento ERS (Padrão)</option>
            <option>2</option>
            <option>3</option>
          </select>
          <i class="fa-solid fa-chevron-down" style="color: rgb(83, 102, 133);"></i>
        </div>
      </div>
      <div class="CentralizarDiv2">
        <a href="php/novo_escopo.php" class="BotaoCriarProjeto">Criar projeto</a>
      </div>
    </div>
  </div>

</body>

</html>