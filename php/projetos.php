<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'auth.php';
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
  $projetoDao   = new \php\ProjetoDao();
  $tipo_usuario = $_SESSION['usuario_tipo']    ?? 'Cliente';
  $id_empresa   = $_SESSION['usuario_empresa'] ?? null;
  $podeGerenciar = in_array($tipo_usuario, ['Administrador', 'Desenvolvedor']);

  $projetos = $projetoDao->read_projetos_por_perfil($tipo_usuario, (int)$id_empresa);
?>

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

          <?php if ($podeGerenciar): ?>
            <label for="CheckboxModal" class="BotaoCadastrar">Cadastrar Projeto +</label>
          <?php endif; ?>
        </div>

        <div class="GradeProjetos">
          <?php foreach ($projetos as $projeto): ?>
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
                  <strong><?= htmlspecialchars($projeto['autor']) ?></strong>
                  <br>
                  <small><?= htmlspecialchars($projeto['modificacao']) ?></small>
                  <br>
                  <small><?= date('d/m/Y H:i', strtotime($projeto['data_modificacao'])) ?></small>
                <?php else: ?>
                  <em>Nenhuma alteração registrada</em>
                <?php endif; ?>
              </p>

              <a href="projeto.php?id=<?= $projeto['id'] ?>" class="BotaoAcessar">
                Acessar projeto
              </a>

            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </main>
  </div>

  <?php if ($podeGerenciar): ?>
  <input type="checkbox" id="CheckboxModal" class="CheckboxModal" />
  <div class="FundoModal">
    <label for="CheckboxModal" class="SombreaModal"></label>
    <div class="ContainerModal">
      <label for="CheckboxModal" class="BotaoVoltar" id="BtnVoltar">
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

      <div class="GrupoFormulario">
        <label class="LabelFormulario" for="InputEmpresa">Empresa</label>
        <div class="AutocompleteWrapper">
          <input
            type="text"
            id="InputEmpresa"
            class="CampoFormulario"
            placeholder="Digite o nome da empresa..."
            autocomplete="off"
          />
          <input type="hidden" id="InputEmpresaId" />
          <div class="ListaAutocomplete" id="ListaEmpresas"></div>
        </div>
      </div>

      <div class="MensagemModal" id="MensagemModal"></div>

      <div class="CentralizarDiv2">
        <button class="BotaoCriar" id="BtnCriarProjeto">Criar Projeto</button>
      </div>
    </div>
  </div>

  <script>
    (() => {
      const inputEmpresa   = document.getElementById('InputEmpresa');
      const inputEmpresaId = document.getElementById('InputEmpresaId');
      const listaEmpresas  = document.getElementById('ListaEmpresas');
      const btnCriar       = document.getElementById('BtnCriarProjeto');
      const mensagemModal  = document.getElementById('MensagemModal');
      const checkboxModal  = document.getElementById('CheckboxModal');
      const btnVoltar      = document.getElementById('BtnVoltar');

      let debounceTimer = null;
      let itemAtivo = -1;

      // ── Autocomplete ──────────────────────────────────────────────
      inputEmpresa.addEventListener('input', () => {
        inputEmpresaId.value = ''; // limpa seleção anterior ao digitar
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => buscarEmpresas(inputEmpresa.value.trim()), 250);
      });

      inputEmpresa.addEventListener('focus', () => {
        buscarEmpresas(inputEmpresa.value.trim());
      });

      inputEmpresa.addEventListener('keydown', (e) => {
        const itens = listaEmpresas.querySelectorAll('.ItemAutocomplete');
        if (!itens.length) return;

        if (e.key === 'ArrowDown') {
          e.preventDefault();
          itemAtivo = Math.min(itemAtivo + 1, itens.length - 1);
          atualizarAtivo(itens);
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          itemAtivo = Math.max(itemAtivo - 1, 0);
          atualizarAtivo(itens);
        } else if (e.key === 'Enter') {
          e.preventDefault();
          if (itemAtivo >= 0) itens[itemAtivo].click();
        } else if (e.key === 'Escape') {
          fecharLista();
        }
      });

      document.addEventListener('click', (e) => {
        if (!e.target.closest('.AutocompleteWrapper')) fecharLista();
      });

      function atualizarAtivo(itens) {
        itens.forEach((el, i) => el.classList.toggle('Ativo', i === itemAtivo));
        if (itemAtivo >= 0) itens[itemAtivo].scrollIntoView({ block: 'nearest' });
      }

      function fecharLista() {
        listaEmpresas.classList.remove('Visivel');
        listaEmpresas.innerHTML = '';
        itemAtivo = -1;
      }

      async function buscarEmpresas(q) {
        try {
          const res  = await fetch(`buscar_empresas.php?q=${encodeURIComponent(q)}`);
          const data = await res.json();
          renderizarLista(data);
        } catch {
          fecharLista();
        }
      }

      function renderizarLista(empresas) {
        listaEmpresas.innerHTML = '';
        itemAtivo = -1;

        if (!empresas.length) {
          fecharLista();
          return;
        }

        empresas.forEach(emp => {
          const div = document.createElement('div');
          div.className   = 'ItemAutocomplete';
          div.textContent = emp.nome_empresa;
          div.addEventListener('mousedown', (e) => {
            e.preventDefault(); // evita blur antes do click
            inputEmpresa.value   = emp.nome_empresa;
            inputEmpresaId.value = emp.id;
            fecharLista();
          });
          listaEmpresas.appendChild(div);
        });

        listaEmpresas.classList.add('Visivel');
      }

      // ── Criar projeto ─────────────────────────────────────────────
      btnCriar.addEventListener('click', async () => {
        const nome      = document.getElementById('InputNomeProjeto').value.trim();
        const descricao = document.getElementById('InputDescricao').value.trim();
        const previsao  = document.getElementById('InputPrevisao').value;
        const idEmpresa = inputEmpresaId.value;

        exibirMensagem('', '');

        if (!nome) {
          exibirMensagem('O nome do projeto é obrigatório.', 'Erro');
          return;
        }
        if (!idEmpresa) {
          exibirMensagem('Selecione uma empresa da lista.', 'Erro');
          return;
        }

        btnCriar.disabled   = true;
        btnCriar.textContent = 'Salvando...';

        const body = new FormData();
        body.append('nome',       nome);
        body.append('descricao',  descricao);
        body.append('previsao',   previsao);
        body.append('id_empresa', idEmpresa);

        try {
          const res  = await fetch('criar_projeto.php', { method: 'POST', body });
          const data = await res.json();

          if (data.sucesso) {
            exibirMensagem(data.mensagem, 'Sucesso');
            limparFormulario();
            // Recarrega a lista após 1.5 s e fecha o modal
            setTimeout(() => {
              checkboxModal.checked = false;
              location.reload();
            }, 1500);
          } else {
            exibirMensagem(data.mensagem, 'Erro');
          }
        } catch {
          exibirMensagem('Erro de comunicação com o servidor.', 'Erro');
        } finally {
          btnCriar.disabled    = false;
          btnCriar.textContent = 'Criar Projeto';
        }
      });

      // ── Utilitários ───────────────────────────────────────────────
      function exibirMensagem(texto, tipo) {
        mensagemModal.textContent = texto;
        mensagemModal.className   = 'MensagemModal' + (tipo ? ' ' + tipo : '');
      }

      function limparFormulario() {
        document.getElementById('InputNomeProjeto').value = '';
        document.getElementById('InputDescricao').value   = '';
        document.getElementById('InputPrevisao').value    = '';
        inputEmpresa.value   = '';
        inputEmpresaId.value = '';
      }

      // Limpa mensagem ao fechar o modal
      btnVoltar.addEventListener('click', () => {
        exibirMensagem('', '');
        limparFormulario();
        fecharLista();
      });
    })();
  </script>
  <?php endif; ?>

</body>
</html>