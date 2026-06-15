<?php
$pdo  = require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/requisito_acoes.php';

$dao   = new \php\ImagensDao($pdo);
$acao  = $_POST['acao'] ?? '';
$volta = 'projeto.php?id=' . (int)($_POST['id_projeto'] ?? 0);

if ($acao === 'upload') {
    $idProjeto    = (int)$_POST['id_projeto'];
    $tituloImagem = trim($_POST['titulo_imagem']  ?? '');
    $tipoDiagrama = trim($_POST['tipo_diagrama']  ?? '');

    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $arquivos            = $_FILES['imagens'] ?? [];

    if (empty($arquivos['name'][0])) {
        header("Location: $volta&erro=nenhum_arquivo");
        exit;
    }

    $arquivo = [
        'name'     => $arquivos['name'][0],
        'tmp_name' => $arquivos['tmp_name'][0],
        'error'    => $arquivos['error'][0],
    ];

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        header("Location: $volta&erro=nenhum_arquivo");
        exit;
    }

    $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $extensoesPermitidas, true)) {
        header("Location: $volta&erro=formato_invalido");
        exit;
    }

    $binario = file_get_contents($arquivo['tmp_name']);
    $mime    = mime_content_type($arquivo['tmp_name']);
    $dataUri = 'data:' . $mime . ';base64,' . base64_encode($binario);

    $dao->salvar($idProjeto, $arquivo['name'], $dataUri, $tituloImagem, $tipoDiagrama);

    $stmtLog = $pdo->prepare("INSERT INTO tb_historico (modificacao, autor, id_requisito, id_projeto) VALUES (?, ?, ?, ?)");
    $nomeExibicao = $tituloImagem !== '' ? $tituloImagem : $arquivo['name'];
    $stmtLog->execute(["Adicionou a imagem \"$nomeExibicao\"", $_SESSION['usuario_id'], null, $idProjeto]);

    header("Location: $volta&sucesso=upload_ok");
    exit;
}

if ($acao === 'excluir') {
    $id        = (int)$_POST['id_imagem'];
    $idProjeto = (int)$_POST['id_projeto'];

    $imagem = $dao->buscarPorId($id);
    $nomeImagem = $imagem['titulo_imagem'] ?? ($imagem['nome_arquivo'] ?? 'imagem');

    $dao->excluir($id);

    $stmtLog = $pdo->prepare("INSERT INTO tb_historico (modificacao, autor, id_requisito, id_projeto) VALUES (?, ?, ?, ?)");
    $stmtLog->execute(["Removeu a imagem \"$nomeImagem\"", $_SESSION['usuario_id'], null, $idProjeto]);

    header("Location: $volta&sucesso=imagem_excluida");
    exit;
}

header("Location: $volta");
exit;