<?php

namespace general\controllers;

use general\helpers\Conexao;

class LocalizacaoController
{

    private $PDO;
    private $tabela = "localizacao";

    function __construct()
    {
        $conexao = new Conexao();
        $this->PDO = $conexao->retornaConexao();
    }

    public function retornaTodos()
    {
        
        $statement = $this->PDO->query(
            'SELECT * FROM localizacao WHERE status = 1'
        );
        
        return $this->processa_resultado($statement);
    }

    public function retornaPorUsuario($usuarioId)
    {
        
        $stmt= $this->PDO->prepare(
            "SELECT * FROM ".
            $this->tabela .
            " WHERE id_usuario = :idusuario "
            );
            $stmt->bindValue(':idusuario', $usuarioId);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            return $result;
    }

    public function retornaPorId($idLocal)
    {
        $stmt= $this->PDO->prepare(
            "SELECT * FROM ".
            $this->tabela .
            " WHERE id = :id"
            );
        $stmt->bindValue(':id', $idLocal);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
    
        return $result;
    }

    public function removerLocal($idLocal)
    {
        try {
            $stmt = $this->PDO->prepare("DELETE FROM localizacao WHERE ".
            "id = :id");
            $stmt->bindValue(":id", $idLocal);
            $stmt->execute();
        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function atualizar($dados)
    {
    }
        
    public function salvar($dados)
    {
        try {

            $stmt = $this->PDO->prepare("INSERT INTO ".
            $this->tabela.
            " (id_usuario,logradouro,complemento,bairro,numero,cidade,uf,cep,latitude,longitude) ".
            "VALUES (:idusuario,:logradouro,:complemento,:bairro,:numero,:cidade,:uf,:cep,:latitude,:longitude)");

            $stmt->bindValue(':idusuario', $dados["id_usuario"]);
            $stmt->bindValue(':logradouro', $dados["logradouro"]);
            $stmt->bindValue(':complemento', $dados["complemento"]);
            $stmt->bindValue(':bairro', $dados["bairro"]);
            $stmt->bindValue(':numero', $dados["numero"]);
            $stmt->bindValue(':cidade', $dados["cidade"]);
            $stmt->bindValue(':uf', $dados["uf"]);
            $stmt->bindValue(':cep', $dados["cep"]);
            $stmt->bindValue(':latitude', $dados["latitude"]);
            $stmt->bindValue(':longitude', $dados["longitude"]);
            $stmt->execute();

        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    private function processa_resultado($statement)
    {
        $results = array();

        if ($statement) {
            while ($row = $statement->fetch(\PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        }

        return $results;
    }
}
