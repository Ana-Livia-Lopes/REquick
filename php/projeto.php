<?php
require_once 'auth.php';
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/requisito_acoes.php';

$idProjeto = (int)($_GET['id'] ?? 0);
if ($idProjeto <= 0) {
    header('Location: projetos.php');
    exit;
}

$stmtLog = $pdo->prepare("
    INSERT INTO tb_log_acesso_projeto (id_usuario, id_projeto, data_acesso)
    VALUES (:id_usuario, :id_projeto, NOW())
");
$stmtLog->execute([
    'id_usuario' => $_SESSION['usuario_id'],
    'id_projeto'  => $idProjeto
]);

$stmtP = $pdo->prepare("SELECT * FROM tb_projetos WHERE id = ?");
$stmtP->execute([$idProjeto]);
$projeto = $stmtP->fetch(PDO::FETCH_ASSOC);
if (!$projeto) {
    header('Location: projetos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'add_comentario') {
    // Valida se quem está enviando é realmente um Cliente
    if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'Cliente') {
        $titulo = trim($_POST['titulo_comentario']);
        $descricao = trim($_POST['descricao_comentario']);
        
        if (!empty($titulo) && !empty($descricao)) {
            $stmtCom = $pdo->prepare("INSERT INTO tb_comentarios (titulo_comentario, descricao_comentario, id_projeto, id_usuario) VALUES (?, ?, ?, ?)");
            $stmtCom->execute([$titulo, $descricao, $idProjeto, $_SESSION['usuario_id']]);
            
            // Recarrega a página com aviso de sucesso
            header("Location: projeto.php?id=" . $idProjeto . "&sucesso=comentario_adicionado");
            exit;
        }
    }
}

$reqDao = new \php\RequisitosDao($pdo);
$imgDao = new \php\ImagensDao($pdo);

$requisitos = $reqDao->listarPorProjeto($idProjeto);
$imagens    = $imgDao->listarPorProjeto($idProjeto);

$stmtComentarios = $pdo->prepare("
    SELECT c.*, u.nome AS nome_autor 
    FROM tb_comentarios c 
    JOIN tb_usuarios u ON c.id_usuario = u.id 
    WHERE c.id_projeto = ? 
    ORDER BY c.data_comentario DESC
");
$stmtComentarios->execute([$idProjeto]);
$comentarios = $stmtComentarios->fetchAll(PDO::FETCH_ASSOC);

$editando    = isset($_GET['editar']) ? (int)$_GET['editar'] : 0;
$reqEdicao   = $editando ? $reqDao->buscarPorId($editando) : null;

$sucesso = $_GET['sucesso'] ?? '';
$erro    = $_GET['erro']    ?? '';

$paginaAtiva = 'projetos';
include 'navbar_lateral.php';

function badgeStatus(int $statusReq): string {
    return $statusReq === 1
        ? '<span class="badge green">Validado</span>'
        : '<span class="badge yellow">Em Andamento</span>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick – <?= htmlspecialchars($projeto['nome_projeto']) ?></title>
    <link rel="stylesheet" href="../css/projeto.css" />
    <link rel="stylesheet" href="../css/comentarios.css" /> <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="AreaRolavel">
    <main class="ConteudoPrincipal">

        <header class="CabecalhoPagina">
            <a href="projetos.php" class="LinkVoltar">
                <i class="fa-solid fa-arrow-left"></i>
                Voltar para projetos
            </a>
            <div class="AcoesCabecalho">
                <a href="exportar_projeto.php?id=<?= $idProjeto ?>" class="btn-header btn-dark">
                    Exportar Projeto <i class="fa-solid fa-file-export"></i>
                </a>
                <a href="novo_escopo.php?id=<?= $idProjeto ?>" class="btn-header btn-dark">
                    Visualizar escopo inicial <i class="fa-solid fa-file-lines"></i>
                </a>
                <label for="CheckboxConvidar" class="btn-header btn-light">
                    Convidar Usuários <i class="fa-solid fa-user-plus"></i>
                </label>
            </div>
        </header>

        <h1 class="TituloProjeto"><?= htmlspecialchars($projeto['nome_projeto']) ?></h1>

        <?php if ($sucesso): ?>
            <div class="alerta alerta-sucesso">
                <?php
                $msgs = [
                    'requisito_adicionado'  => '✅ Requisito adicionado com sucesso!',
                    'requisito_editado'     => '✅ Requisito atualizado com sucesso!',
                    'requisito_excluido'    => '✅ Requisito excluído com sucesso!',
                    'upload_ok'             => '✅ Imagem(ns) enviada(s) com sucesso!',
                    'upload_parcial'        => '⚠️ Algumas imagens foram enviadas (arquivos inválidos ignorados).',
                    'imagem_excluida'       => '✅ Imagem excluída com sucesso!',
                    'comentario_adicionado' => '✅ Comentário adicionado com sucesso!' // Adicionado aviso novo
                ];
                echo $msgs[$sucesso] ?? 'Operação realizada com sucesso.';
                ?>
            </div>
        <?php endif; ?>
        <?php if ($erro): ?>
            <div class="alerta alerta-erro">
                <?php
                $errMsgs = [
                    'titulo_vazio'     => '❌ O título do requisito não pode ser vazio.',
                    'titulo_duplicado' => '❌ Já existe um requisito com esse título neste projeto.',
                    'nenhum_arquivo'   => '❌ Nenhum arquivo foi selecionado.',
                ];
                echo $errMsgs[$erro] ?? 'Ocorreu um erro. Tente novamente.';
                ?>
            </div>
        <?php endif; ?>

        <section class="SecaoComentarios">
            <div class="CabecalhoComentarios">
                <h2 class="TituloComentariosSecao">
                    Comentários e solicitações de mudanças 
                    <?php if (count($comentarios) > 0): ?>
                        <span class="badge-contador">+<?= count($comentarios) ?></span>
                    <?php endif; ?>
                </h2>
                
                <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'Cliente'): ?>
                    <label for="CheckboxComentario" class="btn-header btn-dark" style="cursor: pointer; font-size: 0.85rem;">
                        <i class="fa-solid fa-plus"></i> Adicionar Comentário
                    </label>
                <?php endif; ?>
            </div>

            <div class="ListaComentarios">
                <?php if (count($comentarios) > 0): ?>
                    <?php foreach ($comentarios as $com): ?>
                        <div class="ItemComentario">
                            <h4 class="TituloComentario"><?= htmlspecialchars($com['titulo_comentario']) ?></h4>
                            <p class="TextoComentario"><?= nl2br(htmlspecialchars($com['descricao_comentario'])) ?></p>
                            <span class="AutorComentario">Enviado por <strong><?= htmlspecialchars($com['nome_autor']) ?></strong> em <?= date('d/m/Y H:i', strtotime($com['data_comentario'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="SemComentarios">Nenhum comentário realizado ainda.</p>
                <?php endif; ?>
            </div>
        </section>

        <div id="busca-e-add-requisito" style="margin-top: 30px;">
            <div class="ContainerBusca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="campoBusca" class="CampoBusca" placeholder="Buscar requisitos, tags ou responsáveis nesse projeto" />
            </div>
            
            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] !== 'Cliente'): ?>
                <label for="CheckboxRequisito" class="BotaoCadastrar">
                    Adicionar requisito <i class="fa-solid fa-plus"></i>
                </label>
            <?php endif; ?>
        </div>

        <section class="container-requisitos">
            <div class="tabela-header">
                <div class="col-titulo">Título</div>
                <div class="col-status">Status</div>
                <div class="col-prioridade">Prioridade</div>
                <div class="col-responsavel">Responsável</div>
                
                <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] !== 'Cliente'): ?>
                    <div class="col-acoes">Ações</div>
                <?php endif; ?>
            </div>

            <?php if (empty($requisitos)): ?>
                <div style="padding:24px; text-align:center; color:#718096; font-family:'Sora',sans-serif;">
                    Nenhum requisito cadastrado para este projeto ainda.
                </div>
            <?php else: ?>
                <?php foreach ($requisitos as $req): ?>
                    <div class="tabela-linha" data-titulo="<?= strtolower(htmlspecialchars($req['titulo_requisito'])) ?>">
                        <div class="col-titulo">
                            <div class="icone-req"><i class="fa-solid fa-file-lines"></i></div>
                            <div>
                                <strong><?= htmlspecialchars($req['titulo_requisito']) ?></strong>
                                <span>
                                    <?= $req['autor']
                                        ? 'última vez alterado(a) por ' . htmlspecialchars($req['autor'])
                                        : 'sem alterações registradas' ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-status"><?= badgeStatus((int)$req['status_req']) ?></div>
                        <div class="col-prioridade"><?= $req['prioridade'] ?: '--' ?></div>
                        <div class="col-responsavel"><?= htmlspecialchars($req['responsavel'] ?: '--') ?></div>
                        
                        <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] !== 'Cliente'): ?>
                            <div class="col-acoes">
                                <button
                                    class="btn-excluir"
                                    onclick="confirmarExclusao(<?= $req['id'] ?>, <?= $idProjeto ?>, '<?= addslashes(htmlspecialchars($req['titulo_requisito'])) ?>')"
                                >
                                    <i class="fa-regular fa-trash-can"></i> Excluir
                                </button>

                                <a
                                    href="projeto.php?id=<?= $idProjeto ?>&editar=<?= $req['id'] ?>"
                                    class="btn-editar"
                                >
                                    <i class="fa-regular fa-pen-to-square"></i> Editar
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="tabela-footer"><i class="fa-solid fa-chevron-down"></i></div>
        </section>

        <section class="SecaoImagens">
            <h2 class="TituloSecaoImagens"><i class="fa-regular fa-image"></i> Imagens do Projeto</h2>

            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] !== 'Cliente'): ?>
                <form action="imagem_handler.php" method="POST" enctype="multipart/form-data" id="formUpload">
                    <input type="hidden" name="acao"       value="upload" />
                    <input type="hidden" name="id_projeto" value="<?= $idProjeto ?>" />

                    <div class="ZonaUpload" id="zonaUpload">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p>Arraste imagens aqui ou clique para selecionar</p>
                        <small>Formatos aceitos: JPG, JPEG, PNG · Múltiplos arquivos permitidos</small>
                        <input type="file" name="imagens[]" id="inputImagens"
                               class="InputArquivoOculto" accept=".jpg,.jpeg,.png" multiple />
                    </div>

                    <div class="GradePreview" id="gradePreview"></div>

                    <button type="submit" class="BotaoEnviarImagens" id="btnEnviar">
                        <i class="fa-solid fa-upload"></i> Enviar imagens
                    </button>
                </form>
            <?php endif; ?>

            <?php if (!empty($imagens)): ?>
                <div class="GradeImagens" style="margin-top: 15px;">
                    <?php foreach ($imagens as $img): ?>
                        <div class="CardImagem">
                            <img src="../<?= htmlspecialchars($img['caminho']) ?>"
                                 alt="<?= htmlspecialchars($img['nome_arquivo']) ?>"
                                 loading="lazy" />

                            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] !== 'Cliente'): ?>
                                <form action="imagem_handler.php" method="POST"
                                      onsubmit="return false;"
                                      id="formExcluirImg<?= $img['id'] ?>">
                                    <input type="hidden" name="acao"       value="excluir" />
                                    <input type="hidden" name="id_imagem"  value="<?= $img['id'] ?>" />
                                    <input type="hidden" name="id_projeto" value="<?= $idProjeto ?>" />
                                    <button type="button" class="BotaoExcluirImagem"
                                            onclick="confirmarExclusaoImagem(<?= $img['id'] ?>, '<?= addslashes(htmlspecialchars($img['nome_arquivo'])) ?>')">
                                        <i class="fa-regular fa-trash-can"></i> Excluir
                                    </button>
                                </form>
                            <?php endif; ?>

                            <div class="NomeImagem"><?= htmlspecialchars($img['nome_arquivo']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color:#718096; font-family:'Sora',sans-serif; font-size:.9rem; margin-top:8px;">
                    Nenhuma imagem adicionada ainda.
                </p>
            <?php endif; ?>
        </section>

    </main>

    <aside class="ColunaDireita">
        <div class="card-info-projeto">
            <h3>Informações do projeto</h3>
            <div class="bloco-info">
                <h4>Progresso:</h4>
                <?php
                    $total    = count($requisitos);
                    $validados= count(array_filter($requisitos, fn($r) => $r['status_req'] == 1));
                    $pct      = $total > 0 ? round(($validados / $total) * 100) : 0;
                ?>
                <div class="barra"><div class="progresso" style="width:<?= $pct ?>%"></div></div>
                <p class="percentual"><?= $pct ?>% concluído</p>
            </div>
            <div class="bloco-info linha-info">
                <span class="rotulo">Data de início:</span>
                <span class="valor"><?= date('d/m/Y', strtotime($projeto['data_criacao'])) ?></span>
            </div>
            <div class="bloco-info">
                <span class="rotulo">Total de requisitos:</span>
                <p class="valor-bloco"><?= $total ?> cadastrado(s)</p>
            </div>
        </div>

        <div class="FeedAtividades">
            <h2 class="TituloFeed">Feed de Atividades</h2>
            <ul class="ListaAtividades">
                <?php foreach (array_slice(array_reverse($requisitos), 0, 5) as $req): ?>
                    <li class="ItemAtividade">
                        <div class="AvatarAtividade">
                            <?= strtoupper(substr($req['autor'] ?? 'S', 0, 2)) ?>
                        </div>
                        <div class="ConteudoAtividade">
                            <div class="LinhaAtividade">
                                <p class="NomeAtividade"><?= htmlspecialchars($req['autor'] ?? 'Sistema') ?></p>
                                <p class="TempoAtividade">
                                    <?= $req['data_modificacao']
                                        ? date('d/m H:i', strtotime($req['data_modificacao']))
                                        : '--' ?>
                                </p>
                            </div>
                            <p class="TextoAtividade">
                                modificou "<?= htmlspecialchars($req['titulo_requisito']) ?>"
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>
</div>


<?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'Cliente'): ?>
<div class="WrapperModal">
    <input type="checkbox" id="CheckboxComentario" class="CheckboxModal" />
    <div class="FundoModal">
        <label for="CheckboxComentario" class="SombreaModal"></label>
        <div class="ContainerModal">
            <label for="CheckboxComentario" class="BotaoVoltar">
                <i class="fa-solid fa-angle-left"></i> Voltar
            </label>
            <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogoModal" />
            <h2 class="TituloModal">Novo Comentário</h2>

            <form action="projeto.php?id=<?= $idProjeto ?>" method="POST">
                <input type="hidden" name="acao" value="add_comentario" />

                <div class="GrupoFormulario">
                    <label class="LabelFormulario">Título do comentário *</label>
                    <input type="text" name="titulo_comentario" class="CampoFormulario"
                        placeholder="Ex: Dúvida sobre o RF01" required />
                </div>
                
                <div class="GrupoFormulario">
                    <label class="LabelFormulario">Descrição *</label>
                    <textarea name="descricao_comentario" class="CampoFormulario CampoTextarea"
                            placeholder="Descreva a sua dúvida ou sugestão de melhoria..." required></textarea>
                </div>
                
                <div class="CentralizarDiv2">
                    <button type="submit" class="BotaoCriar">Salvar Comentário</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>


<div class="WrapperModal">
    <input type="checkbox" id="CheckboxRequisito" class="CheckboxModal"
        <?= ($editando === 0 && $erro) ? 'checked' : '' ?> />

    <div class="FundoModal">
        <label for="CheckboxRequisito" class="SombreaModal"></label>
        <div class="ContainerModal">
            <label for="CheckboxRequisito" class="BotaoVoltar">
                <i class="fa-solid fa-angle-left"></i> Voltar
            </label>
            <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogoModal" />
            <h2 class="TituloModal">Novo Requisito</h2>

            <form action="requisito_handler.php" method="POST">
                <input type="hidden" name="acao"       value="adicionar" />
                <input type="hidden" name="id_projeto" value="<?= $idProjeto ?>" />

                <div class="GrupoFormulario">
                    <label class="LabelFormulario" for="add_titulo">Título do requisito *</label>
                    <input type="text" id="add_titulo" name="titulo" class="CampoFormulario"
                        placeholder="Ex: RF01 - Login via biometria" required />
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario" for="add_descricao">Descrição</label>
                    <textarea id="add_descricao" name="descricao" class="CampoFormulario CampoTextarea"
                            placeholder="Descreva o requisito..."></textarea>
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario" for="add_tipo">Tipo</label>
                    <select id="add_tipo" name="tipo" class="CampoFormulario">
                        <option value="Funcional">Funcional</option>
                        <option value="Nao Funcional">Não Funcional</option>
                    </select>
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario" for="add_prioridade">Prioridade</label>
                    <select id="add_prioridade" name="prioridade" class="CampoFormulario">
                        <option value="">-- Selecione --</option>
                        <option value="Alta">Alta</option>
                        <option value="Média">Média</option>
                        <option value="Baixa">Baixa</option>
                    </select>
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario" for="add_responsavel">Responsável</label>
                    <input type="text" id="add_responsavel" name="responsavel" class="CampoFormulario"
                        placeholder="Nome do responsável" />
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario" for="add_autor">Autor (quem está criando)</label>
                    <input type="text" id="add_autor" name="autor" class="CampoFormulario"
                        placeholder="Seu nome" />
                </div>
                <div class="CentralizarDiv2">
                    <button type="submit" class="BotaoCriar">Criar Requisito</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php if ($reqEdicao): ?>
<div class="WrapperModal">
    <input type="checkbox" id="CheckboxEditar" class="CheckboxModal" checked />
    <div class="FundoModal">
        <label for="CheckboxEditar" class="SombreaModal"></label>
        <div class="ContainerModal">
            <a href="projeto.php?id=<?= $idProjeto ?>" class="BotaoVoltar">
                <i class="fa-solid fa-angle-left"></i> Voltar
            </a>
            <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogoModal" />
            <h2 class="TituloModal">Editar Requisito</h2>

            <form action="requisito_handler.php" method="POST">
                <input type="hidden" name="acao"          value="editar" />
                <input type="hidden" name="id_requisito"  value="<?= $reqEdicao['id'] ?>" />
                <input type="hidden" name="id_projeto"    value="<?= $idProjeto ?>" />

                <div class="GrupoFormulario">
                    <label class="LabelFormulario">Título do requisito *</label>
                    <input type="text" name="titulo" class="CampoFormulario"
                        value="<?= htmlspecialchars($reqEdicao['titulo_requisito']) ?>" required />
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario">Descrição</label>
                    <textarea name="descricao" class="CampoFormulario CampoTextarea"><?= htmlspecialchars($reqEdicao['descricao_requisito']) ?></textarea>
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario">Tipo</label>
                    <select name="tipo" class="CampoFormulario">
                        <option value="Funcional"     <?= $reqEdicao['tipo']==='Funcional'     ? 'selected':'' ?>>Funcional</option>
                        <option value="Nao Funcional" <?= $reqEdicao['tipo']==='Nao Funcional' ? 'selected':'' ?>>Não Funcional</option>
                    </select>
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario">Prioridade</label>
                    <select name="prioridade" class="CampoFormulario">
                        <option value="">-- Selecione --</option>
                        <?php foreach (['Alta','Média','Baixa'] as $p): ?>
                            <option value="<?= $p ?>" <?= $reqEdicao['prioridade']===$p ? 'selected':'' ?>><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario">Responsável</label>
                    <input type="text" name="responsavel" class="CampoFormulario"
                        value="<?= htmlspecialchars($reqEdicao['responsavel'] ?? '') ?>" />
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario">Status</label>
                    <select name="status" class="CampoFormulario">
                        <option value="0" <?= $reqEdicao['status_req']==0 ? 'selected':'' ?>>Em Andamento</option>
                        <option value="1" <?= $reqEdicao['status_req']==1 ? 'selected':'' ?>>Validado</option>
                    </select>
                </div>
                <div class="GrupoFormulario">
                    <label class="LabelFormulario">Autor (quem está editando)</label>
                    <input type="text" name="autor" class="CampoFormulario"
                        value="<?= htmlspecialchars($reqEdicao['autor'] ?? '') ?>" />
                </div>
                <div class="CentralizarDiv2">
                    <button type="submit" class="BotaoCriar">Salvar alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="WrapperModal">
    <input type="checkbox" id="CheckboxConvidar" class="CheckboxModal" />
    <div class="FundoModal">
        <label for="CheckboxConvidar" class="SombreaModal"></label>
        <div class="ContainerModal">
            <label for="CheckboxConvidar" class="BotaoVoltar">
                <i class="fa-solid fa-angle-left"></i> Voltar
            </label>
            <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogoModal">
            <h2 class="TituloModal">Convidar Pessoas</h2>
            <div class="GrupoFormulario">
                <label class="LabelFormulario">E-mail do usuário</label>
                <input type="email" class="CampoFormulario" placeholder="Digite o e-mail">
            </div>
            <div class="GrupoFormulario">
                <label class="LabelFormulario">Função no projeto</label>
                <select class="CampoFormulario">
                    <option>Responsável</option><option>Cliente</option>
                    <option>Desenvolvedor</option><option>Analista</option><option>Visualizador</option>
                </select>
            </div>
            <div class="GrupoFormulario">
                <label class="LabelFormulario">Mensagem (opcional)</label>
                <textarea class="CampoFormulario CampoTextarea" placeholder="Digite uma mensagem..."></textarea>
            </div>
            <div class="CentralizarDiv2">
                <button class="BotaoCriar">Convidar</button>
            </div>
        </div>
    </div>
</div>

<form id="formExcluirReq" action="requisito_handler.php" method="POST" style="display:none">
    <input type="hidden" name="acao"          value="excluir" />
    <input type="hidden" name="id_requisito"  id="hiddenIdReq" />
    <input type="hidden" name="id_projeto"    value="<?= $idProjeto ?>" />
</form>

<script>
document.getElementById('campoBusca').addEventListener('input', function () {
    const termo = this.value.toLowerCase();
    document.querySelectorAll('.tabela-linha').forEach(linha => {
        const titulo = linha.dataset.titulo || '';
        linha.style.display = titulo.includes(termo) ? '' : 'none';
    });
});

function confirmarExclusao(id, idProjeto, titulo) {
    Swal.fire({
        title: 'Excluir requisito?',
        html: `Tem certeza que deseja excluir <strong>"${titulo}"</strong>?<br>Esta ação não pode ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor:  '#4a5568',
        confirmButtonText:  'Sim, excluir',
        cancelButtonText:   'Cancelar',
        background: '#1e1e2e',
        color: '#e2e8f0',
        iconColor: '#f59e0b',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById('hiddenIdReq').value = id;
            document.getElementById('formExcluirReq').submit();
        }
    });
}

function confirmarExclusaoImagem(id, nome) {
    Swal.fire({
        title: 'Excluir imagem?',
        html: `Deseja remover <strong>"${nome}"</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor:  '#4a5568',
        confirmButtonText:  'Sim, excluir',
        cancelButtonText:   'Cancelar',
        background: '#1e1e2e',
        color: '#e2e8f0',
        iconColor: '#f59e0b',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById('formExcluirImg' + id).submit();
        }
    });
}

const zona      = document.getElementById('zonaUpload');
const inputFile = document.getElementById('inputImagens');
const preview   = document.getElementById('gradePreview');
const btnEnviar = document.getElementById('btnEnviar');
let   arquivos  = new DataTransfer(); // mantém lista de arquivos

if(zona && inputFile) { // Proteção caso o usuário logado seja cliente e a Div nem seja renderizada no html
    zona.addEventListener('click', () => inputFile.click());

    zona.addEventListener('dragover', e => { e.preventDefault(); zona.classList.add('arrastando'); });
    zona.addEventListener('dragleave', () => zona.classList.remove('arrastando'));
    zona.addEventListener('drop', e => {
        e.preventDefault();
        zona.classList.remove('arrastando');
        processarArquivos(e.dataTransfer.files);
    });

    inputFile.addEventListener('change', () => processarArquivos(inputFile.files));
}

function processarArquivos(novos) {
    const permitidos = ['image/jpeg', 'image/jpg', 'image/png'];
    Array.from(novos).forEach(file => {
        if (!permitidos.includes(file.type)) return;
        arquivos.items.add(file);
        const reader = new FileReader();
        reader.onload = e => {
            const wrap  = document.createElement('div');
            wrap.classList.add('ItemPreview');
            const img   = document.createElement('img');
            img.src     = e.target.result;
            const btn   = document.createElement('button');
            btn.type    = 'button';
            btn.classList.add('RemoverPreview');
            btn.innerHTML = '×';
            btn.onclick = () => {
                const nova = new DataTransfer();
                Array.from(arquivos.files)
                     .filter(f => f !== file)
                     .forEach(f => nova.items.add(f));
                arquivos = nova;
                inputFile.files = arquivos.files;
                wrap.remove();
                if (arquivos.files.length === 0) btnEnviar.classList.remove('visivel');
            };
            wrap.appendChild(img);
            wrap.appendChild(btn);
            preview.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
    inputFile.files = arquivos.files;
    if (arquivos.files.length > 0) btnEnviar.classList.add('visivel');
}
</script>

</body>
</html>
