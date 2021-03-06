<?php

namespace general\controllers;

use general\helpers\Conexao;

class CurtidaApi
{
    private $PDO;
    private $tabela = "curtidas";
    private $caminhoLocal;
    private $imgUserDefault = "/webfiles/images/perfil.png";

    function __construct($baseUrl = null)
    {
        $conexao = new Conexao();
        $this->PDO = $conexao->retornaConexao();
        $this->caminhoLocal = $baseUrl;
    }

    public function fazCurtida($idPost,$idUsuario){

        if ($this->verificaCurtidaDesativada($idPost,$idUsuario)){

            try{
                $stmt = $this->PDO->prepare("UPDATE curtidas SET ".
                    "status_curtida = 1".
                    " WHERE id_post = :idpost AND id_usuario = :idusuario");
                $stmt->bindValue(':idpost', $idPost);
                $stmt->bindValue(':idusuario', $idUsuario);
                $stmt->execute();
                if ($stmt->rowCount()){
                    $valido["mensagem"] = "Curtida realizada com sucesso";
                    $valido["status"] = 1;
                }else{
                    $valido["mensagem"] = "Erro ao curtir";
                    $valido["status"] = 0;
                }
                return $valido;
            }catch (Exception $ex) {
                $this->PDO->rollback();
                $valido["mensagem"] = "Erro ao curtir";
                $valido["status"] = 0;
                return $valido;
            }

        }else{

            try{
                $stmt = $this->PDO->prepare("INSERT INTO ".
                    $this->tabela .
                    " (id_post,id_usuario) VALUES(:idpost,:idusuario)");
                $stmt->bindValue(':idpost', $idPost);
                $stmt->bindValue(':idusuario', $idUsuario); 
                $stmt->execute();
                if ($stmt->rowCount()){
                    $valido["mensagem"] = "Curtida realizada com sucesso";
                    $valido["status"] = 1;
                }else{
                    $valido["mensagem"] = "Erro ao curtir";
                    $valido["status"] = 0;
                }
                return $valido;
            }catch (Exception $ex) {
                $this->PDO->rollback();
                $valido["mensagem"] = "Erro ao curtir";
                $valido["status"] = 0;
                return $valido;
            }

        }
        
    }

    public function desfazerCurtida($idPost,$idUsuario){
        try{
            $stmt = $this->PDO->prepare("UPDATE curtidas SET ".
                "status_curtida = 0".
                " WHERE id_post = :idpost AND id_usuario = :idusuario");
            $stmt->bindValue(':idpost',$idPost);
            $stmt->bindValue(':idusuario', $idUsuario);
            $stmt->execute();
            if ($stmt->rowCount()){
                $valido["mensagem"] = "Curtida desfeita com sucesso";
                $valido["status"] = 1;
            }else{
                $valido["mensagem"] = "Erro ao desfazer curtida ou já foi desfeita";
                $valido["status"] = 0;
            }
            return $valido;
        }catch (Exception $ex) {
            $this->PDO->rollback();
            $valido["mensagem"] = "Erro ao desfazer curtida";
            $valido["status"] = 0;
            return $valido;
        }
    }

    public function JaCurtiu($idPost,$idUsuario){

        $stmt = $this->PDO->prepare("SELECT id FROM ".
            $this->tabela .
            " WHERE id_post = :idpost AND id_usuario = :idusuario".
            " AND status_curtida = 1");
        $stmt->bindValue(':idpost', $idPost);
        $stmt->bindValue(':idusuario', $idUsuario);
        $stmt->execute();
        $result = $stmt->fetchALL();

        if (count($result) > 0){
            return true;
        }else{
            return false;
        }

    }

    public function retornaCurtidas($idPost){
        $retorno = [];

        $stmt= $this->PDO->prepare(
            "SELECT usuario.id as id_usuario,usuario.nome as nome_usuario,usuario.caminho_imagem as imagem_usuario, ".
            " curtidas.data_curtida as data_curtida,curtidas.id_post as id_post, ".
            " usuario.usuario as login ".
            " FROM curtidas INNER JOIN usuario WHERE usuario.id = curtidas.id_usuario ".
            " AND usuario.status_usuario = 1 AND curtidas.status_curtida = 1 ".
            " AND curtidas.id_post = :idpost"
            );
        $stmt->bindValue(':idpost',$idPost);
        $stmt->execute();
        $result = $stmt->fetchALL(\PDO::FETCH_OBJ);
        foreach ($result as $key => $value) {
            if ($value->imagem_usuario != null){
                $caminho = $value->imagem_usuario;
                $value->imagem_usuario = $this->caminhoLocal . $caminho;
            }else{
                $value->imagem_usuario = $this->caminhoLocal . $imgUserDefault;
            }
        }

        return $result;
    }

    public function retornaQuantLikes($idUsuario){
        $stmt = $this->PDO->prepare("SELECT id FROM " .
        $this->tabela .
        " WHERE id_usuario = :idusuario AND status_curtida = 1");
        $stmt->bindValue(':idusuario', $idUsuario);
        $stmt->execute();
        $result = $stmt->fetchALL();

        return count($result);
    }

    //Funções Privadas
    private function verificaCurtidaDesativada($idPost,$idUsuario){

        $stmt = $this->PDO->prepare("SELECT id FROM ".
            $this->tabela .
            " WHERE id_post = :idpost AND id_usuario = :idusuario".
            " AND status_curtida = 0");
        $stmt->bindValue(':idpost',$idPost);
        $stmt->bindValue(':idusuario',$idUsuario);
        $stmt->execute();
        $result = $stmt->fetchALL();

        if (count($result) > 0){
            return true;
        }else{
            return false;
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