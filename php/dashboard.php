<?php
require_once 'auth.php';
require_once '../config/conexao.php';
require_once '../components/modal.php';
require_once 'projeto_acoes.php';

// ─── Controle de acesso por tipo de usuário ───────────────────────────────────
$tipoUsuario      = $_SESSION['usuario_tipo']    ?? '';
$idEmpresaUsuario = $_SESSION['usuario_empresa'] ?? null;
$idUsuarioLogado  = $_SESSION['usuario_id']      ?? null;
$isCliente        = ($tipoUsuario === 'Cliente');
// ─────────────────────────────────────────────────────────────────────────────

$erro = '';

// Apenas não-clientes podem criar projetos
if (!$isCliente && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomeProjeto = trim($_POST['InputNomeProjeto']);

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
        $projetoObj = new \php\Projeto();
        $projetoDao = new \php\ProjetoDao($projetoObj);
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
        // ─── ESTATÍSTICAS ────────────────────────────────────────────────────────────

        // Projetos ativos: possuem ao menos um requisito ainda não validado
        $sqlAtivos = "
            SELECT COUNT(DISTINCT p.id) AS total
            FROM tb_projetos p
            INNER JOIN tb_requisitos r ON r.id_projeto = p.id
            WHERE r.status_req = 0
        ";

        // Projetos concluídos: possuem requisitos e TODOS estão validados (status_req = 1)
        $sqlConcluidos = "
            SELECT COUNT(*) AS total FROM (
                SELECT p.id
                FROM tb_projetos p
                INNER JOIN tb_requisitos r ON r.id_projeto = p.id
                GROUP BY p.id
                HAVING COUNT(r.id) > 0
                   AND SUM(CASE WHEN r.status_req = 0 THEN 1 ELSE 0 END) = 0
            ) AS concluidos
        ";

        // Requisitos pendentes: não validados
        $sqlReqPendentes = "
            SELECT COUNT(*) AS total
            FROM tb_requisitos
            WHERE status_req = 0
        ";

        $totalAtivos     = $pdo->query($sqlAtivos)->fetchColumn();
        $totalConcluidos = $pdo->query($sqlConcluidos)->fetchColumn();
        $totalPendentes  = $pdo->query($sqlReqPendentes)->fetchColumn();
        // ─────────────────────────────────────────────────────────────────────────────
        ?>

        <div class="GradeEstatisticas">
            <div class="CardEstatistica CardEstatisticaEscuro">
                <div class="InfoCard">
                    <p class="LabelCard">Projetos ativos</p>
                    <p class="NumeroCard"><?= $totalAtivos ?></p>
                    <p class="SubtituloCard">Em produção</p>
                </div>
                <div class="IconeCard"><i class="fa-regular fa-folder"></i></div>
            </div>

            <div class="CardEstatistica CardEstatisticaEscuro">
                <div class="InfoCard">
                    <p class="LabelCard">Requisitos pendentes</p>
                    <p class="NumeroCard"><?= $totalPendentes ?></p>
                    <p class="SubtituloCard">Aguardando validação</p>
                </div>
                <div class="IconeCard"><i class="fa-regular fa-clock"></i></div>
            </div>

            <div class="CardEstatistica CardEstatisticaEscuro">
                <div class="InfoCard">
                    <p class="LabelCard">Projetos concluídos</p>
                    <p class="NumeroCard"><?= $totalConcluidos ?></p>
                    <p class="SubtituloCard">Todos requisitos validados</p>
                </div>
                <div class="IconeCard"><i class="fa-regular fa-circle-check"></i></div>
            </div>
        </div>

        <div class="SecaoProjetos">
            <div class="CabecalhoProjetos">
                <h2 class="TituloSecao">Projetos Recentes</h2>

                <?php if (!$isCliente): ?>
                    <label for="CheckboxModal" class="BotaoCadastrar">Cadastrar Projeto +</label>
                <?php endif; ?>
            </div>

            <div class="GradeProjetos">
                <?php
                // ─── ÚLTIMOS 3 PROJETOS ACESSADOS PELO USUÁRIO LOGADO ────────────────────
                $sqlRecentes = "
                    SELECT
                        p.id,
                        p.nome_projeto,
                        p.descricao,
                        p.id_empresa,
                        h_ult.autor            AS autor,
                        h_ult.modificacao      AS modificacao,
                        h_ult.data_modificacao AS data_modificacao
                    FROM tb_projetos p

                    INNER JOIN (
                        SELECT id_projeto, MAX(data_acesso) AS ultimo_acesso
                        FROM tb_log_acesso_projeto
                        WHERE id_usuario = :id_usuario
                        GROUP BY id_projeto
                        ORDER BY ultimo_acesso DESC
                        LIMIT 3
                    ) AS acessos ON acessos.id_projeto = p.id

                    LEFT JOIN (
                        SELECT
                            r.id_projeto,
                            u.nome         AS autor,
                            h.modificacao,
                            h.data         AS data_modificacao
                        FROM tb_historico h
                        INNER JOIN tb_usuarios   u ON u.id = h.autor
                        INNER JOIN tb_requisitos r ON r.id = h.id_requisito
                        WHERE h.data = (
                            SELECT MAX(h2.data)
                            FROM tb_historico h2
                            INNER JOIN tb_requisitos r2 ON r2.id = h2.id_requisito
                            WHERE r2.id_projeto = r.id_projeto
                        )
                    ) AS h_ult ON h_ult.id_projeto = p.id
                ";

                if ($isCliente) {
                    $sqlRecentes .= " WHERE p.id_empresa = :id_empresa ";
                }

                $sqlRecentes .= " ORDER BY acessos.ultimo_acesso DESC";

                $stmtRecentes = $pdo->prepare($sqlRecentes);
                $stmtRecentes->bindValue(':id_usuario', $idUsuarioLogado, PDO::PARAM_INT);
                if ($isCliente) {
                    $stmtRecentes->bindValue(':id_empresa', $idEmpresaUsuario, PDO::PARAM_INT);
                }
                $stmtRecentes->execute();
                $projetosRecentes = $stmtRecentes->fetchAll(PDO::FETCH_ASSOC);
                // ─────────────────────────────────────────────────────────────────────────
                ?>

                <?php if (empty($projetosRecentes)): ?>
                    <p style="color: #666; font-size: 0.9rem;">Nenhum projeto acessado recentemente.</p>
                <?php else: ?>
                    <?php foreach ($projetosRecentes as $projeto): ?>
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
                <?php endif; ?>

                <a href="projetos.php" class="BotaoProjetos">Ver todos Projetos</a>
            </div>
        </div>

        <div class="FeedAtividades">
            <h2 class="TituloFeed">Feed de Atividades</h2>
            <ul class="ListaAtividades">
                <?php
                if (!function_exists('tempoAtras')) {
                    function tempoAtras($data) {
                        $agora     = new DateTime();
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

                // ─── FEED: filtrado por perfil do usuário logado ─────────────────────────
                // Administrador vê tudo; Desenvolvedor/Cliente vê apenas projetos
                // da sua empresa + projetos convidados via tb_projeto_usuarios

                if ($tipoUsuario === 'Administrador') {
                    $sqlFeed = "
                        SELECT
                            h.modificacao,
                            h.data,
                            u.nome  AS autor_nome,
                            r.titulo_requisito,
                            p.nome_projeto
                        FROM tb_historico h
                        INNER JOIN tb_usuarios u ON u.id = h.autor
                        INNER JOIN tb_projetos p ON p.id = h.id_projeto
                        LEFT JOIN  tb_requisitos r ON r.id = h.id_requisito
                        ORDER BY h.data DESC
                        LIMIT 10
                    ";
                    $paramsFeed = [];
                } else {
                    $sqlFeed = "
                        SELECT
                            h.modificacao,
                            h.data,
                            u.nome  AS autor_nome,
                            r.titulo_requisito,
                            p.nome_projeto
                        FROM tb_historico h
                        INNER JOIN tb_usuarios u ON u.id = h.autor
                        INNER JOIN tb_projetos p ON p.id = h.id_projeto
                        LEFT JOIN  tb_requisitos r ON r.id = h.id_requisito
                        WHERE (
                            p.id_empresa = :id_empresa
                            OR p.id IN (
                                SELECT id_projeto
                                FROM tb_projeto_usuarios
                                WHERE id_usuario = :id_usuario
                            )
                        )
                        ORDER BY h.data DESC
                        LIMIT 10
                    ";
                    $paramsFeed = [
                        ':id_empresa' => $idEmpresaUsuario,
                        ':id_usuario' => $idUsuarioLogado,
                    ];
                }

                try {
                    $stmtFeed = $pdo->prepare($sqlFeed);
                    $stmtFeed->execute($paramsFeed);
                    $atividades = $stmtFeed->fetchAll(PDO::FETCH_ASSOC);

                    if (count($atividades) > 0) {
                        foreach ($atividades as $atividade):
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
                                        <?= htmlspecialchars($atividade['modificacao']) ?>
                                        <?php if (!empty($atividade['titulo_requisito'])): ?>
                                            em <strong><?= htmlspecialchars($atividade['titulo_requisito']) ?></strong>
                                        <?php endif; ?>
                                        no projeto <em><?= htmlspecialchars($atividade['nome_projeto']) ?></em>
                                    </p>
                                </div>
                            </li>
                <?php
                        endforeach;
                    } else {
                        echo "<p style='color: #666; font-size: 0.9rem;'>Nenhuma atividade recente encontrada nos seus projetos.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p style='color: red;'>Erro ao carregar o feed de atividades: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                // ─────────────────────────────────────────────────────────────────────────
                ?>
            </ul>
        </div>
    </div>

    <?php if (!$isCliente): ?>
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
                        <?php if ($erro != ''): ?>
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
                        <label class="LabelFormulario" for="selectEmpresa">Empresa</label>
                        <select name="selectEmpresa" id="selectEmpresa" class="CampoFormulario">
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

        <?php renderModal('modalSucesso', 'Sucesso', '<p>Projeto criado!</p>'); ?>
        <?php if (isset($_SESSION['modal_sucesso'])): ?>
            <script>abrirModal('modalSucesso');</script>
            <?php unset($_SESSION['modal_sucesso']); ?>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>