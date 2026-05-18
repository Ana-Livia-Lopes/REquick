<?php
// php/imagem_handler.php
// Processa upload e exclusão de imagens via POST

require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/requisito_acoes.php';

$dao   = new \php\ImagensDao($conn);
$acao  = $_POST['acao']     ?? '';
$volta = 'projeto.php?id=' . (int)($_POST['id_projeto'] ?? 0);

// ── Upload ───────────────────────────────────────────────────────────────────
if ($acao === 'upload') {
    $idProjeto = (int)$_POST['id_projeto'];

    // Pasta de destino: uploads/imagens/<id_projeto>/
    $pastaBase = __DIR__ . '/../uploads/imagens/' . $idProjeto . '/';
    if (!is_dir($pastaBase)) {
        mkdir($pastaBase, 0755, true);
    }

    $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
    $erros = [];

    // Suporte a múltiplos arquivos (input name="imagens[]")
    $arquivos = $_FILES['imagens'] ?? [];

    if (empty($arquivos['name'][0])) {
        header("Location: $volta&erro=nenhum_arquivo");
        exit;
    }

    $total = count($arquivos['name']);

    for ($i = 0; $i < $total; $i++) {
        if ($arquivos['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $nomeOriginal = basename($arquivos['name'][$i]);
        $ext          = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

        if (!in_array($ext, $extensoesPermitidas, true)) {
            $erros[] = $nomeOriginal;
            continue;
        }

        // Nome único para evitar colisões
        $nomeArquivo = uniqid('img_', true) . '.' . $ext;
        $caminhoFull = $pastaBase . $nomeArquivo;
        // Caminho relativo armazenado no banco (a partir da raiz do site)
        $caminhoWeb  = 'uploads/imagens/' . $idProjeto . '/' . $nomeArquivo;

        if (move_uploaded_file($arquivos['tmp_name'][$i], $caminhoFull)) {
            $dao->salvar($idProjeto, $nomeOriginal, $caminhoWeb);
        }
    }

    if (!empty($erros)) {
        header("Location: $volta&sucesso=upload_parcial");
    } else {
        header("Location: $volta&sucesso=upload_ok");
    }
    exit;
}

// ── Excluir ──────────────────────────────────────────────────────────────────
if ($acao === 'excluir') {
    $id        = (int)$_POST['id_imagem'];
    $idProjeto = (int)$_POST['id_projeto'];
    $imagem    = $dao->buscarPorId($id);

    if ($imagem) {
        // Remove o arquivo físico
        $caminhoFisico = __DIR__ . '/../' . $imagem['caminho'];
        if (file_exists($caminhoFisico)) {
            unlink($caminhoFisico);
        }
        $dao->excluir($id);
    }

    header("Location: $volta&sucesso=imagem_excluida");
    exit;
}

// Fallback
header("Location: $volta");
exit;