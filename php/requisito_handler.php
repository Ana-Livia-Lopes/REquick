<?php

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/requisito_acoes.php';

$dao    = new \php\RequisitosDao($pdo);
$acao   = $_POST['acao']       ?? '';
$volta  = 'projeto.php?id=' . (int)($_POST['id_projeto'] ?? 0);

if ($acao === 'adicionar') {
    $idProjeto   = (int)$_POST['id_projeto'];
    $titulo      = trim($_POST['titulo']      ?? '');
    $descricao   = trim($_POST['descricao']   ?? '');
    $tipo        = $_POST['tipo']             ?? 'Funcional';
    $prioridade  = $_POST['prioridade']       ?? '';
    $responsavel = trim($_POST['responsavel'] ?? '');
    $autor       = trim($_POST['autor']       ?? 'Sistema');

    if ($titulo === '') {
        header("Location: $volta&erro=titulo_vazio");
        exit;
    }

    if ($dao->tituloExiste($titulo, $idProjeto)) {
        header("Location: $volta&erro=titulo_duplicado");
        exit;
    }

    $dao->criar($idProjeto, $titulo, $descricao, $tipo, $prioridade, $responsavel, $autor);
    header("Location: $volta&sucesso=requisito_adicionado");
    exit;
}

if ($acao === 'editar') {
    $id          = (int)$_POST['id_requisito'];
    $idProjeto   = (int)$_POST['id_projeto'];
    $titulo      = trim($_POST['titulo']       ?? '');
    $descricao   = trim($_POST['descricao']    ?? '');
    $tipo        = $_POST['tipo']              ?? 'Funcional';
    $prioridade  = $_POST['prioridade']        ?? '';
    $responsavel = trim($_POST['responsavel']  ?? '');
    $status      = $_POST['status']            ?? '0';
    $autor       = trim($_POST['autor']        ?? 'Sistema');

    if ($titulo === '') {
        header("Location: $volta&erro=titulo_vazio");
        exit;
    }

    if ($dao->tituloExiste($titulo, $idProjeto, $id)) {
        header("Location: $volta&erro=titulo_duplicado");
        exit;
    }

    $dao->editar($id, $titulo, $descricao, $tipo, $prioridade, $responsavel, $status, $autor);
    header("Location: $volta&sucesso=requisito_editado");
    exit;
}

if ($acao === 'excluir') {
    $id = (int)$_POST['id_requisito'];
    $dao->excluir($id);
    header("Location: $volta&sucesso=requisito_excluido");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'toggle_status') {
    $idReq = (int)$_POST['id_requisito'];
    $idProjeto = (int)$_POST['id_projeto'];
    $statusAtual = (int)$_POST['status_atual'];
    $novoStatus = $statusAtual === 1 ? 0 : 1;

    // Atualiza o status
    $stmt = $pdo->prepare("UPDATE tb_requisitos SET status_req = ? WHERE id = ?");
    $stmt->execute([$novoStatus, $idReq]);

    // Registra na SUA tb_historico
    $acao = $novoStatus === 1 ? "Validou o requisito" : "Marcou o requisito como 'Em andamento'";
    
    $stmtLog = $pdo->prepare("INSERT INTO tb_historico (modificacao, autor, id_requisito) VALUES (?, ?, ?)");
    $stmtLog->execute([$acao, $_SESSION['usuario_id'], $idReq]);

    header("Location: projeto.php?id=" . $idProjeto . "&sucesso=requisito_editado");
    exit;
}

// Fallback
header("Location: $volta");
exit;