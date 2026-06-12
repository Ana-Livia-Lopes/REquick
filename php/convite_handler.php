<?php
require_once 'auth.php'; // Puxa a sessão e verifica se está logado
require_once __DIR__ . '/../config/conexao.php'; // Puxa o $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProjeto = (int)($_POST['id_projeto'] ?? 0);
    $idUsuarioConvidado = (int)($_POST['id_usuario'] ?? 0);

    // Validação básica se os IDs vieram corretos
    if ($idProjeto <= 0 || $idUsuarioConvidado <= 0) {
        header("Location: projetos.php");
        exit;
    }

    // Trava de Segurança: Apenas Administrador ou Desenvolvedor podem convidar
    $tipoUsuarioLogado = $_SESSION['usuario_tipo'] ?? '';
    if (!in_array($tipoUsuarioLogado, ['Administrador', 'Desenvolvedor'])) {
        header("Location: projeto.php?id=$idProjeto&erro=sem_permissao");
        exit;
    }

    try {
        // 1. Verifica se o usuário já faz parte deste projeto para evitar duplicidade
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM tb_projeto_usuarios WHERE id_projeto = ? AND id_usuario = ?");
        $stmtCheck->execute([$idProjeto, $idUsuarioConvidado]);
        $jaExiste = $stmtCheck->fetchColumn();

        if ($jaExiste > 0) {
            header("Location: projeto.php?id=$idProjeto&erro=usuario_ja_convidado");
            exit;
        }

        // 2. Insere o usuário no projeto
        $stmtInsert = $pdo->prepare("INSERT INTO tb_projeto_usuarios (id_projeto, id_usuario) VALUES (?, ?)");
        $stmtInsert->execute([$idProjeto, $idUsuarioConvidado]);

        // 3. Busca o nome do usuário convidado para deixar o feed mais dinâmico
        $stmtNome = $pdo->prepare("SELECT nome FROM tb_usuarios WHERE id = ?");
        $stmtNome->execute([$idUsuarioConvidado]);
        $nomeConvidado = $stmtNome->fetchColumn();

        // 4. Registra a ação no FEED DE ATIVIDADES (tb_historico)
        // Usamos o id_projeto para que apareça no feed do projeto geral
        $acao = "Convidou o(a) usuário(a) " . ($nomeConvidado ? $nomeConvidado : "Desconhecido") . " para o projeto";
        $stmtLog = $pdo->prepare("INSERT INTO tb_historico (modificacao, autor, id_projeto) VALUES (?, ?, ?)");
        $stmtLog->execute([$acao, $_SESSION['usuario_id'], $idProjeto]);

        // Redireciona com mensagem de sucesso
        header("Location: projeto.php?id=$idProjeto&sucesso=usuario_convidado");
        exit;

    } catch (PDOException $e) {
        // Registra o erro internamente e devolve para a tela
        error_log("Erro ao convidar usuário: " . $e->getMessage());
        header("Location: projeto.php?id=$idProjeto&erro=erro_banco");
        exit;
    }
} else {
    // Se tentarem acessar a URL direto sem enviar o formulário
    header("Location: projetos.php");
    exit;
}