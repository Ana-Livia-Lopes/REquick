<?php

require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/requisito_acoes.php';

$dao    = new \php\RequisitosDao($conn);
$acao   = $_POST['acao']       ?? '';
$volta  = 'projeto.php?id=' . (int)($_POST['id_projeto'] ?? 0);

if ($acao === 'adicionar') {
    $idProjeto  = (int)$_POST['id_projeto'];
    $titulo     = trim($_POST['titulo']      ?? '');
    $descricao  = trim($_POST['descricao']   ?? '');
    $tipo       = $_POST['tipo']             ?? 'Funcional';
    $prioridade = $_POST['prioridade']       ?? '';
    $responsavel= trim($_POST['responsavel'] ?? '');
    $autor      = trim($_POST['autor']       ?? 'Sistema');

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
    $id         = (int)$_POST['id_requisito'];
    $idProjeto  = (int)$_POST['id_projeto'];
    $titulo     = trim($_POST['titulo']      ?? '');
    $descricao  = trim($_POST['descricao']   ?? '');
    $tipo       = $_POST['tipo']             ?? 'Funcional';
    $prioridade = $_POST['prioridade']       ?? '';
    $responsavel= trim($_POST['responsavel'] ?? '');
    $status     = $_POST['status']           ?? '0';
    $autor      = trim($_POST['autor']       ?? 'Sistema');

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

// Fallback
header("Location: $volta");
exit;