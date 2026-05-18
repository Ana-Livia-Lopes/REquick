<?php
namespace php;

require_once __DIR__ . '/conexao.php';
class RequisitosDao
{
    private \mysqli $conn;

    public function __construct(\mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function listarPorProjeto(int $idProjeto): array
    {
        $sql  = "SELECT * FROM tb_requisitos WHERE id_projeto = ? ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $idProjeto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $sql  = "SELECT * FROM tb_requisitos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    public function tituloExiste(string $titulo, int $idProjeto, int $ignorarId = 0): bool
    {
        $sql  = "SELECT id FROM tb_requisitos 
                 WHERE titulo_requisito = ? AND id_projeto = ? AND id != ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sii', $titulo, $idProjeto, $ignorarId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function criar(
        int    $idProjeto,
        string $titulo,
        string $descricao,
        string $tipo,
        string $prioridade,
        string $responsavel,
        string $autor
    ): bool {
        $sql = "INSERT INTO tb_requisitos 
                    (id_projeto, titulo_requisito, descricao_requisito,
                     tipo, prioridade, responsavel, autor, status_req)
                VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            'issssss',
            $idProjeto, $titulo, $descricao,
            $tipo, $prioridade, $responsavel, $autor
        );
        return $stmt->execute();
    }

    public function editar(
        int    $id,
        string $titulo,
        string $descricao,
        string $tipo,
        string $prioridade,
        string $responsavel,
        string $status,
        string $autor
    ): bool {
        $statusInt = ($status === '1') ? 1 : 0;

        $sql = "UPDATE tb_requisitos SET
                    titulo_requisito     = ?,
                    descricao_requisito  = ?,
                    tipo                 = ?,
                    prioridade           = ?,
                    responsavel          = ?,
                    status_req           = ?,
                    autor                = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            'sssssisi',
            $titulo, $descricao, $tipo,
            $prioridade, $responsavel, $statusInt, $autor, $id
        );
        return $stmt->execute();
    }

    public function excluir(int $id): bool
    {
        $sql  = "DELETE FROM tb_requisitos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}

class ImagensDao
{
    private \mysqli $conn;

    public function __construct(\mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function listarPorProjeto(int $idProjeto): array
    {
        $sql  = "SELECT * FROM tb_imagens_projeto WHERE id_projeto = ? ORDER BY data_upload ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $idProjeto);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function salvar(int $idProjeto, string $nomeArquivo, string $caminho): bool
    {
        $sql  = "INSERT INTO tb_imagens_projeto (id_projeto, nome_arquivo, caminho) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iss', $idProjeto, $nomeArquivo, $caminho);
        return $stmt->execute();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql  = "SELECT * FROM tb_imagens_projeto WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function excluir(int $id): bool
    {
        $sql  = "DELETE FROM tb_imagens_projeto WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}