<?php
namespace php;

require_once __DIR__ . '/../config/conexao.php';

class RequisitosDao
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarPorProjeto(int $idProjeto): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tb_requisitos WHERE id_projeto = ? ORDER BY id ASC");
        $stmt->execute([$idProjeto]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tb_requisitos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function tituloExiste(string $titulo, int $idProjeto, int $ignorarId = 0): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM tb_requisitos 
             WHERE titulo_requisito = ? AND id_projeto = ? AND id != ?"
        );
        $stmt->execute([$titulo, $idProjeto, $ignorarId]);
        return $stmt->rowCount() > 0;
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
        $stmt = $this->pdo->prepare(
            "INSERT INTO tb_requisitos 
                (id_projeto, titulo_requisito, descricao_requisito,
                 tipo, prioridade, responsavel, autor, status_req)
             VALUES (?, ?, ?, ?, ?, ?, ?, 0)"
        );
        return $stmt->execute([$idProjeto, $titulo, $descricao, $tipo, $prioridade, $responsavel, $autor]);
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

        $stmt = $this->pdo->prepare(
            "UPDATE tb_requisitos SET
                titulo_requisito    = ?,
                descricao_requisito = ?,
                tipo                = ?,
                prioridade          = ?,
                responsavel         = ?,
                status_req          = ?,
                autor               = ?
             WHERE id = ?"
        );
        return $stmt->execute([$titulo, $descricao, $tipo, $prioridade, $responsavel, $statusInt, $autor, $id]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM tb_requisitos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

class ImagensDao
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarPorProjeto(int $idProjeto): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM tb_imagens_projeto WHERE id_projeto = ? ORDER BY data_upload ASC"
        );
        $stmt->execute([$idProjeto]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function salvar(int $idProjeto, string $nomeArquivo, string $caminho): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO tb_imagens_projeto (id_projeto, nome_arquivo, caminho) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$idProjeto, $nomeArquivo, $caminho]);
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tb_imagens_projeto WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM tb_imagens_projeto WHERE id = ?");
        return $stmt->execute([$id]);
    }
}