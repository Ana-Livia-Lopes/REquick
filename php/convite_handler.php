<?php
require_once 'auth.php';
require_once __DIR__ . '/../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProjeto = (int)($_POST['id_projeto'] ?? 0);
    $idUsuarioConvidado = (int)($_POST['id_usuario'] ?? 0);

    if ($idProjeto <= 0 || $idUsuarioConvidado <= 0) {
        header("Location: projetos.php");
        exit;
    }

    $tipoUsuarioLogado = $_SESSION['usuario_tipo'] ?? '';
    if (!in_array($tipoUsuarioLogado, ['Administrador', 'Desenvolvedor'])) {
        header("Location: projeto.php?id=$idProjeto&erro=sem_permissao");
        exit;
    }

    try {
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM tb_projeto_usuarios WHERE id_projeto = ? AND id_usuario = ?");
        $stmtCheck->execute([$idProjeto, $idUsuarioConvidado]);
        $jaExiste = $stmtCheck->fetchColumn();

        if ($jaExiste > 0) {
            header("Location: projeto.php?id=$idProjeto&erro=usuario_ja_convidado");
            exit;
        }

        $stmtInsert = $pdo->prepare("INSERT INTO tb_projeto_usuarios (id_projeto, id_usuario) VALUES (?, ?)");
        $stmtInsert->execute([$idProjeto, $idUsuarioConvidado]);

        $stmtNome = $pdo->prepare("SELECT nome FROM tb_usuarios WHERE id = ?");
        $stmtNome->execute([$idUsuarioConvidado]);
        $nomeConvidado = $stmtNome->fetchColumn();

        $acao = "Convidou o(a) usuário(a) " . ($nomeConvidado ? $nomeConvidado : "Desconhecido") . " para o projeto";
        $stmtLog = $pdo->prepare("INSERT INTO tb_historico (modificacao, autor, id_projeto) VALUES (?, ?, ?)");
        $stmtLog->execute([$acao, $_SESSION['usuario_id'], $idProjeto]);

        header("Location: projeto.php?id=$idProjeto&sucesso=usuario_convidado");
        exit;

    } catch (PDOException $e) {
        error_log("Erro ao convidar usuário: " . $e->getMessage());
        header("Location: projeto.php?id=$idProjeto&erro=erro_banco");
        exit;
    }
} else {
    header("Location: projetos.php");
    exit;
}