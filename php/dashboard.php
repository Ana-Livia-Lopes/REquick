<?php
require_once 'auth.php';
require_once '../config/conexao.php';
require_once '../components/modal.php';
require_once 'projeto_acoes.php'; 

$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomeProjeto = trim($_POST['InputNomeProjeto']);

    // VALIDAÇÃO
    if ($nomeProjeto === '') {

        $erro = 'O nome do projeto é obrigatório.';

    } else {

        $projeto = new \php\Projeto();

        $projeto->setNome($nomeProjeto);
        $projeto->setDescricao($_POST['InputDescricao']);
        $projeto->setIdEmpresa($_POST['selectEmpresa']);

        $projeto->setDataCriacao(date('Y-m-d'));

        $projetoDao = new \php\ProjetoDao();
        $projetoDao->create($projeto);

        $_SESSION['modal_sucesso'] = true;

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick – Dashboard</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/projetos.css" />
    <link rel="stylesheet" href="../css/modal.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    
    <?php
        $projetos = new \php\Projeto();
        $projetoDao = new \php\ProjetoDao($projetos);
        $empresaDao = new \php\EmpresaDao();
        date_default_timezone_set('America/Sao_Paulo');
    ?>
    <?php $paginaAtiva = 'dashboard'; include 'navbar_lateral.php'; ?>

    <div class="ConteudoPrincipal">
        
        <header class="CabecalhoPagina">
            <h1 class="TituloBoasVindas">Olá, <?= isset($_SESSION['usuario_nome']) ? htmlspecialchars($_SESSION['usuario_nome']) : 'Visitante' ?>! Bem-vindo(a) ao Dashboard.</h1>
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

                        <a href="projeto.php?id=<?= $projeto['id'] ?>" class="BotaoAcessar">
                            Acessar projeto
                        </a>

                    </div>
                <?php endforeach; ?>
                <a href="projetos.php" class="BotaoProjetos">Ver todos Projetos</label></a>

            </div>
            
        </div>

        <div class="FeedAtividades">
            <h2 class="TituloFeed">Feed de Atividades</h2>
            <ul class="ListaAtividades">
                <?php
                // 1. Função para calcular o tempo decorrido
                if (!function_exists('tempoAtras')) {
                    function tempoAtras($data) {
                        $agora = new DateTime();
                        $dataBanco = new DateTime($data);
                        $diferenca = $agora->diff($dataBanco);

                        if ($diferenca->y > 0) return $diferenca->y . ' ano(s) atrás';
                        if ($diferenca->m > 0) return $diferenca->m . ' mês(es) atrás';
                        if ($diferenca->d > 0) return $diferenca->d . ' dia(s) atrás';
                        if ($diferenca->h > 0) return $diferenca->h . ' hora(s) atrás';
                        if ($diferenca->i > 0) return $diferenca->i . ' min atrás';
                        return 'agora mesmo';
                    }
                }

                // 2. Variável da empresa salva na sessão
                $id_empresa = $_SESSION['usuario_empresa'];
                
                // 3. Query SQL
                $sqlFeed = "SELECT 
                        h.modificacao, 
                        h.data, 
                        u.nome AS autor_nome, 
                        r.titulo_requisito, 
                        p.nome_projeto
                    FROM tb_historico h
                    INNER JOIN tb_usuarios u ON h.autor = u.id
                    INNER JOIN tb_requisitos r ON h.id_requisito = r.id
                    INNER JOIN tb_projetos p ON r.id_projeto = p.id
                    WHERE p.id_empresa = :id_empresa
                    ORDER BY h.data DESC
                    LIMIT 10"; 

                try {
                    // PRESTA ATENÇÃO AQUI: Agora estamos usando o seu $pdo
                    $stmtFeed = $pdo->prepare($sqlFeed);
                    $stmtFeed->execute(['id_empresa' => $id_empresa]);
                    $atividades = $stmtFeed->fetchAll(PDO::FETCH_ASSOC);

                    if (count($atividades) > 0) {
                        foreach ($atividades as $atividade): 
                            
                            // Lógica do Avatar
                            $partesNome = explode(' ', trim($atividade['autor_nome']));
                            if (count($partesNome) >= 2) {
                                $iniciais = strtoupper(substr($partesNome[0], 0, 1) . substr(end($partesNome), 0, 1));
                            } else {
                                $iniciais = strtoupper(substr($partesNome[0], 0, 2));
                            }
                ?>
                            <li class="ItemAtividade">
                                <div class="AvatarAtividade"><?= htmlspecialchars($iniciais) ?></div>
                                <div class="ConteudoAtividade">
                                    <div class="LinhaAtividade">
                                        <p class="NomeAtividade"><?= htmlspecialchars($atividade['autor_nome']) ?></p>
                                        <p class="TempoAtividade"><?= tempoAtras($atividade['data']) ?></p>
                                    </div>
                                    <p class="TextoAtividade">
                                        <?= htmlspecialchars($atividade['modificacao']) ?> em 
                                        <strong><?= htmlspecialchars($atividade['titulo_requisito']) ?></strong> 
                                        no projeto <em><?= htmlspecialchars($atividade['nome_projeto']) ?></em>
                                    </p>
                                </div>
                            </li>
                <?php 
                        endforeach; 
                    } else {
                        // Mensagem caso a empresa ainda não tenha histórico
                        echo "<p style='color: #666; font-size: 0.9rem;'>Nenhuma atividade recente encontrada nos projetos da sua empresa.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p style='color: red;'>Erro ao carregar o feed de atividades: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                ?>
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
        <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogoModal" />
      </div>
      <h2 class="TituloModal">Novo Projeto</h2>
      <form action="" method="post">
        
          <div class="GrupoFormulario">
            <label class="LabelFormulario" for="InputNomeProjeto">Nome do projeto</label>
            <input type="text" name="InputNomeProjeto" id="InputNomeProjeto" class="CampoFormulario" placeholder="Digite o nome do projeto" required/>
            <?php if($erro != ''): ?>

                <div class="MensagemErro" style="color: red;">
                    <?= htmlspecialchars($erro) ?>
                </div>

            <?php endif; ?>
          </div>
          <div class="GrupoFormulario">
            <label class="LabelFormulario" for="InputDescricao">Descrição</label>
            <textarea id="InputDescricao" name="InputDescricao" class="CampoFormulario CampoTextarea" placeholder="Digite a descrição do projeto"></textarea>
          </div>
            <div class="GrupoFormulario">
                <label class="LabelFormulario" for="selectEmpresa">
                    Empresa
                </label>

                <select 
                    name="selectEmpresa" 
                    id="selectEmpresa" 
                    class="CampoFormulario">

                    <?php foreach ($empresaDao->read() as $empresa): ?>

                        <option value="<?= htmlspecialchars($empresa['id']) ?>">
                            <?= htmlspecialchars($empresa['nome_empresa']) ?>
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
  <script src="../assets/modal.js"></script>
  <!-- modal -->
    <?php
        renderModal('modalSucesso','Sucesso','<p>Projeto criado!</p>');
    ?>
    <?php if(isset($_SESSION['modal_sucesso'])): ?>

    <script>

        abrirModal('modalSucesso');

    </script>

    <?php unset($_SESSION['modal_sucesso']); ?>

    <?php endif; ?>
</body>
</html>