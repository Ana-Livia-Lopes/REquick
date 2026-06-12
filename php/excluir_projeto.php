<?php
require_once 'auth.php';
$pdo = require_once __DIR__ . '/../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_projeto'])) {
    
    $idProjeto = (int)$_POST['id_projeto'];
    $tipo_usuario = $_SESSION['usuario_tipo'] ?? '';
    if ($tipo_usuario === 'Administrador' && $idProjeto > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM tb_projetos WHERE id = ?");
            $stmt->execute([$idProjeto]);
            
        } catch (PDOException $e) {
            error_log("Erro ao excluir projeto: " . $e->getMessage());
        }
    }
}

header("Location: projetos.php");
exit;