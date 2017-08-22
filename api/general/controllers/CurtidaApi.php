<?php

namespace general\controllers;

class CurtidaApi
{
    private $PDO;
    private $tabela = "curtidas";

    function __construct()
    {
        $this->PDO = new \PDO('mysql:host=localhost;dbname=social_work;charset=utf8', 'root', ''); //Conexão
        $this->PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
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
            }catch (Exception $ex) {
                $this->PDO->rollback();
            }

        }else{

            try{
                $stmt = $this->PDO->prepare("INSERT INTO ".
                $this->tabela .
                " (id_post,id_usuario) VALUES(:idpost,:idusuario)");
                $stmt->bindValue(':idpost', $idPost);
                $stmt->bindValue(':idusuario', $idUsuario); 
                $stmt->execute();
            }catch (Exception $ex) {
                $this->PDO->rollback();
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
        }catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function retornaQuantidadeCurtidas($idPost){
         $stmt = $this->PDO->prepare("SELECT id FROM ".
         $this->tabela .
         " WHERE id_post = :idpost and status_curtida = 1");
         $stmt->bindValue(':idpost',$idPost);
         $stmt->execute();
         $result = $stmt->fetchALL();

         return count($result);
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
        $stmt = $this->PDO->prepare("SELECT * FROM ".
        $this->tabela .
        " WHERE id_post = :idpost AND status_curtida = 1");
        $stmt->bindValue(':idpost',$idPost);
        $stmt->execute();
        $result = $stmt->fetchALL();

        if (count($result) > 0){
            $lista = [];
            $userController = new \general\controllers\UsuarioController();
            for ($i = 0;$i < count($result); $i++){
                $lista[$i]["usuario"] = $userController->retornarPorId($result[$i]["id_usuario"]);
                $lista[$i]["data"] = date( 'd/m/y ',strtotime($result[$i]["data_curtida"]));
                $lista[$i]["id"] = $result[$i]["id"];
            }
            return $lista;
        }else {
            return $result;
        }

    }

    public function numCurtidasPorUsuario($usuarioId){
        
        $stmt = $this->PDO->prepare("SELECT id FROM " .
        $this->tabela .
        " WHERE id_usuario = :idusuario AND status_curtida = 1");
        $stmt->bindValue(':idusuario', $usuarioId);
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
    
}