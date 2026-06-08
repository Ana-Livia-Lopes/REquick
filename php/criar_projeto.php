<?php
require_once 'auth.php';
require_once 'projeto_acoes.php';

header('Content-Type: application/json');

// Apenas Administrador e Desenvolvedor podem criar projetos
$tipo_usuario = $_SESSION['usuario_tipo'] ?? 'Cliente';
if (!in_array($tipo_usuario, ['Administrador', 'Desenvolvedor'])) {
    http_response_code(403);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Sem permissão.']);
    exit;
}

$nome      = trim($_POST['nome']      ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$previsao  = trim($_POST['previsao']  ?? '');
$id_empresa = (int) ($_POST['id_empresa'] ?? 0);

if (empty($nome) || $id_empresa <= 0) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nome e empresa são obrigatórios.']);
    exit;
}

// Data de criação: previsão informada ou hoje
$data_criacao = !empty($previsao) ? $previsao : date('Y-m-d');

try {
    $projeto = new \php\Projeto();
    $projeto->setNome($nome);
    $projeto->setDescricao($descricao);
    $projeto->setIdEmpresa($id_empresa);
    $projeto->setDataCriacao($data_criacao);

    $dao = new \php\ProjetoDao();
    $dao->create($projeto);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Projeto criado com sucesso!']);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao criar projeto: ' . $e->getMessage()]);
}

?>