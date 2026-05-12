<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $tipo = isset($_POST['tipo_requisito']) ? $_POST['tipo_requisito'] : '';
    $titulo = $_POST['titulo_requisito'];
    $descricao = $_POST['descricao_requisito'];

    if ($tipo == 'nao_funcional') {
        $tipo_formatado = 'Nao Funcional';
    } else {
        $tipo_formatado = 'Funcional';
    }

    $sql = "INSERT INTO tb_requisitos (titulo_requisito, descricao_requisito, tipo, id_projeto) VALUES (?, ?, ?, NULL)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $titulo, $descricao, $tipo_formatado);

    if ($stmt->execute()) {
        echo "<script>alert('Requisito adicionado com sucesso!'); window.location.href='projeto.php';</script>";
    } else {
        echo "Erro ao inserir: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>