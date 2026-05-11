<?php

namespace php;

use PDO;

class Conexao{
    private static $instance;
    
    public static function getConn(){
        require_once 'conexao.php';

        if(!isset(self::$instance)):
            self::$instance = new \PDO('mysql:host=' . $host . ';dbname=' . $banco . ';charset=utf8',$usuario,$senha);
        endif;
        return self::$instance;
    }
}     

class Projeto{
    private $id, $nome, $descricao;

    public function getId(){
        return $this->id;
    }
    public function getNome(){
        return $this->nome;
    }
    public function setNome($n){
        $this->nome = $n;
    }
    public function getDescricao(){
        return $this->descricao;
    }
    public function setDescricao($d){
        $this->descricao = $d;
    }
}

class ProjetoDao{
    public function create(Projeto $p){

        $sql = 'INSERT INTO tb_projetos (nome_projeto, descricao) VALUES (?,?)';
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindValue(1, $p->getNome());//1 se refere ao nome (a primeir interrogação)
        $stmt->bindValue(2, $p->getDescricao());
        $stmt->execute();
    }
    public function read(){
        $sql = "
            SELECT 
                p.id,
                p.nome_projeto,
                p.descricao,
                p.data_criacao,

                h.modificacao,
                h.data AS data_modificacao,

                u.nome AS autor

            FROM tb_projetos p

            LEFT JOIN tb_historico h 
                ON h.id = (
                    SELECT h2.id
                    FROM tb_historico h2
                    WHERE h2.id_requisito = p.id
                    ORDER BY h2.data DESC
                    LIMIT 1
                )

            LEFT JOIN tb_usuarios u
                ON u.id = h.autor

            ORDER BY p.id DESC

            LIMIT 6
        ";

        $result = Conexao::getConn()->query($sql);

        if ($result) {

            $dados = $result->fetchAll(PDO::FETCH_ASSOC);

            return $dados;
        }

        return [];
    }
    public function countStatus(){

        $sql = "
            SELECT
                COUNT(CASE WHEN status_projeto = 1 THEN 1 END) AS ativos,
                COUNT(CASE WHEN status_projeto = 0 THEN 1 END) AS desativados
            FROM tb_projetos
        ";

        $result = Conexao::getConn()->query($sql);

        if($result){

            $dados = $result->fetch(PDO::FETCH_ASSOC);

            return $dados;
        }

        return [
            'ativos' => 0,
            'desativados' => 0
        ];
    }
    public function countRequisitos(){

        $sql = "
            SELECT
                COUNT(CASE WHEN status_req = 1 THEN 1 END) AS ativos,
                COUNT(CASE WHEN status_req = 0 THEN 1 END) AS desativados
            FROM tb_requisitos
        ";

        $result = Conexao::getConn()->query($sql);

        if($result){
            return $result->fetch(PDO::FETCH_ASSOC);
        }

        return [
            'ativos' => 0,
            'desativados' => 0
        ];
    }
    // public function update(Produto $p){
    //     require_once 'conexao.php';

    //     $sql = 'UPDATE produtos SET nome = ?, descricao = ? WHERE id = ?';

    //     $stmt = $conn->prepare($sql);
    //     $stmt->bindValue(1, $p->getNome());
    //     $stmt->bindValue(2, $p->getDescricao());
    //     $stmt->bindValue(3, $p->getId());

    //     $stmt->execute();
    // }
    // public function delete($id){
    //     $sql = 'DELETE FROM produtos WHERE id = ?';
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bindValue(1, $id);
    //     $stmt->execute();
    // }
}