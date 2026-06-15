<?php

namespace php;

use PDO;

class Conexao {
    private static $instance;

    public static function getConn(): PDO {
        if (!isset(self::$instance)) {
            self::$instance = new PDO(
                'mysql:host=localhost;dbname=bd_requick;charset=utf8mb4',
                'root',
                '',
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }
        return self::$instance;
    }
}

class Projeto {
    private $id, $nome, $descricao, $idEmpresa, $dataCriacao;

    public function setDataCriacao($dataCriacao) { $this->dataCriacao = $dataCriacao; }
    public function getDataCriacao()              { return $this->dataCriacao; }
    public function setIdEmpresa($idEmpresa)      { $this->idEmpresa = $idEmpresa; }
    public function getIdEmpresa()                { return $this->idEmpresa; }
    public function getId()                       { return $this->id; }
    public function getNome()                     { return $this->nome; }
    public function setNome($n)                   { $this->nome = $n; }
    public function getDescricao()                { return $this->descricao; }
    public function setDescricao($d)              { $this->descricao = $d; }
}

class EmpresaDao {

    public function read(): array {
        $sql  = "SELECT * FROM tb_empresa ORDER BY nome_empresa ASC";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class ProjetoDao {

    private function getIdEmpresaLogado(): int {
        return (int) ($_SESSION['usuario_empresa'] ?? 0);
    }

    public function create(Projeto $p): void {
        $sql = "INSERT INTO tb_projetos
                    (nome_projeto, data_criacao, descricao, id_empresa, status_projeto)
                VALUES (?, ?, ?, ?, 0)";

        $stmt = Conexao::getConn()->prepare($sql);

        $descricao = trim($p->getDescricao()) === '' ? null : $p->getDescricao();

        $stmt->bindValue(1, $p->getNome());
        $stmt->bindValue(2, $p->getDataCriacao());
        $stmt->bindValue(3, $descricao, $descricao === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(4, $p->getIdEmpresa(), PDO::PARAM_INT);

        $stmt->execute();
    }

    public function read(): array {
        $idEmpresa = $this->getIdEmpresaLogado();

        $sql = "
            SELECT
                p.id,
                p.nome_projeto,
                p.descricao,
                p.data_criacao,
                h.modificacao,
                h.data    AS data_modificacao,
                u.nome    AS autor
            FROM tb_projetos p
            LEFT JOIN tb_historico h
                ON h.id = (
                    SELECT h2.id FROM tb_historico h2
                    WHERE h2.id_requisito = p.id
                    ORDER BY h2.data DESC LIMIT 1
                )
            LEFT JOIN tb_usuarios u ON u.id = h.autor
            WHERE p.id_empresa = :idEmpresa
            ORDER BY p.id DESC
            LIMIT 6
        ";

        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindValue(':idEmpresa', $idEmpresa, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read_projetos(): array {
        $idEmpresa = $this->getIdEmpresaLogado();

        $sql = "
            SELECT
                p.id,
                p.nome_projeto,
                p.descricao,
                p.data_criacao,
                h.modificacao,
                h.data    AS data_modificacao,
                u.nome    AS autor
            FROM tb_projetos p
            LEFT JOIN tb_historico h
                ON h.id = (
                    SELECT h2.id FROM tb_historico h2
                    WHERE h2.id_requisito = p.id
                    ORDER BY h2.data DESC LIMIT 1
                )
            LEFT JOIN tb_usuarios u ON u.id = h.autor
            WHERE p.id_empresa = :idEmpresa
            ORDER BY p.id DESC
        ";

        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindValue(':idEmpresa', $idEmpresa, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countStatus(): array {
        $idEmpresa = $this->getIdEmpresaLogado();

        $sql = "
            SELECT
                COUNT(CASE WHEN status_projeto = 1 THEN 1 END) AS ativos,
                COUNT(CASE WHEN status_projeto = 0 THEN 1 END) AS desativados
            FROM tb_projetos
            WHERE id_empresa = :idEmpresa
        ";

        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindValue(':idEmpresa', $idEmpresa, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['ativos' => 0, 'desativados' => 0];
    }

    public function countRequisitos(): array {
        $idEmpresa = $this->getIdEmpresaLogado();

        $sql = "
            SELECT
                COUNT(CASE WHEN r.status_req = 1 THEN 1 END) AS ativos,
                COUNT(CASE WHEN r.status_req = 0 THEN 1 END) AS desativados
            FROM tb_requisitos r
            INNER JOIN tb_projetos p ON p.id = r.id_projeto
            WHERE p.id_empresa = :idEmpresa
        ";

        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindValue(':idEmpresa', $idEmpresa, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['ativos' => 0, 'desativados' => 0];
    }

    public function read_projetos_por_perfil(string $tipo_usuario, ?int $id_empresa, int $id_usuario = 0): array
    {
        if (in_array($tipo_usuario, ['Administrador', 'Desenvolvedor'])) {
            $stmt = Conexao::getConn()->prepare("
                SELECT
                    p.id,
                    p.nome_projeto,
                    p.descricao,
                    p.data_criacao  AS data_modificacao,
                    h.modificacao,
                    u.nome          AS autor
                FROM tb_projetos p
                LEFT JOIN tb_historico h
                    ON h.id = (
                        SELECT h2.id FROM tb_historico h2
                        WHERE h2.id_projeto = p.id
                        ORDER BY h2.data DESC LIMIT 1
                    )
                LEFT JOIN tb_usuarios u ON u.id = h.autor
                ORDER BY p.id DESC
            ");
            $stmt->execute();
        } else {

            $stmt = Conexao::getConn()->prepare("
                SELECT
                    p.id,
                    p.nome_projeto,
                    p.descricao,
                    p.data_criacao  AS data_modificacao,
                    h.modificacao,
                    u.nome          AS autor
                FROM tb_projetos p
                LEFT JOIN tb_historico h
                    ON h.id = (
                        SELECT h2.id FROM tb_historico h2
                        WHERE h2.id_projeto = p.id
                        ORDER BY h2.data DESC LIMIT 1
                    )
                LEFT JOIN tb_usuarios u ON u.id = h.autor
                
                /* Filtra pela empresa do usuário OU se o ID do usuário está na tabela de convites */
                WHERE p.id_empresa = :id_empresa 
                OR p.id IN (
                    SELECT id_projeto 
                    FROM tb_projeto_usuarios 
                    WHERE id_usuario = :id_usuario
                )
                ORDER BY p.id DESC
            ");
            
            $stmt->execute([
                ':id_empresa' => $id_empresa,
                ':id_usuario' => $id_usuario
            ]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}