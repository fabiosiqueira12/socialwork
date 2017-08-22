<?php

namespace general\controllers;

class RelacionamentoApi
{
    private $PDO;
    private $tabela = "relacionamento";
    
    function __construct()
    {
        $this->PDO = new \PDO('mysql:host=localhost;dbname=social_work;charset=utf8', 'root', ''); //Conexão
        $this->PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
    }

    public function solicitaAmizade($userPediu, $userRecebeu)
    {
        try {
            $stmt = $this->PDO->prepare("INSERT INTO ".
            $this->tabela .
            "(id_usuario_princ,id_user_seguidor)".
            " VALUES (:usuarioprinc,:usuarioseguidor)");

            $stmt->bindValue(':usuarioprinc', $userPediu);
            $stmt->bindValue(":usuarioseguidor", $userRecebeu);
            $stmt->execute();
        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function aceitarSolicitacao($idSolicitacao)
    {

        try {
            $stmt = $this->PDO->prepare("UPDATE relacionamento SET ".
            "status_relacionamento = 2".
            " WHERE id = :idsolic");
            $stmt->bindValue(':idsolic', $idSolicitacao);
            $stmt->execute();
        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function recusarSolicitacao($idSolicitacao)
    {

        try {
            $stmt = $this->PDO->prepare("UPDATE relacionamento SET ".
            "status_relacionamento = 0".
            " WHERE id = :idsolic");
            $stmt->bindValue(':idsolic', $idSolicitacao);
            $stmt->execute();
        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function desfazerAmizade($usuarioLogado,$usuarioPerfil){
        try{
            
            $stmt = $this->PDO->prepare("UPDATE relacionamento SET ".
            " status_relacionamento = 0".
            " WHERE id_usuario_princ = :usuariologado ".
            " and id_user_seguidor = :usuarioperfil".
            " OR id_usuario_princ = :usuarioperfil and id_user_seguidor = :usuariologado".
            " AND status_relacionamento = 2");
            $stmt->bindValue(':usuariologado', $usuarioLogado);
            $stmt->bindValue(':usuarioperfil', $usuarioPerfil);
            $stmt->execute();

        }catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function retornaQuantidadeDeAmigos($usuarioId)
    {
        $stmt = $this->PDO->prepare("SELECT id FROM ".
        $this->tabela .
        " WHERE ( id_usuario_princ = :usuarioid".
        " OR id_user_seguidor = :usuarioid ) AND status_relacionamento = 2");
        $stmt->bindValue(':usuarioid',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchALL();

        return count($result);
    }

    public function retornaQuantidadeDeSolicitacoes($usuarioId)
    {
        $stmt = $this->PDO->prepare("SELECT id FROM ".
        $this->tabela .
        " WHERE id_user_seguidor = :usuarioid and status_relacionamento = 1");
        $stmt->bindValue(':usuarioid',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchALL();

        return count($result);

    }

    public function retornaSolicitacoes($usuarioId)
    {
        $stmt = $this->PDO->prepare("SELECT * FROM ".
        $this->tabela .
        " WHERE id_user_seguidor = :usuarioid and status_relacionamento = 1");
        $stmt->bindValue(':usuarioid',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchALL();
        
        if (count($result) > 0){
            $lista = [];
            $userController = new \general\controllers\UsuarioController();
            for ($i = 0;$i < count($result); $i++){

                $lista[$i]["usuario"] = $userController->retornarPorId($result[$i]["id_usuario_princ"]);
                $lista[$i]["data"] = date( 'd/m/y ',strtotime($result[$i]["data_solicitacao"]));
                $lista[$i]["id"] = $result[$i]["id"];
                $lista[$i]["hora"] = date ( 'h:m' ,strtotime($result[$i]["data_solicitacao"]));

            }
            return $lista;

        }else {
            return $result;
        }
    }

    public function retornaAmigos($usuarioId)
    {

        $stmt = $this->PDO->prepare("SELECT id_usuario_princ,id_user_seguidor FROM ".
        $this->tabela .
        " WHERE ( id_usuario_princ = :idusuario".
        " OR id_user_seguidor = :idusuario ) and status_relacionamento = 2");
        $stmt->bindValue(':idusuario',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) > 0){
            
            $lista = [];
            $userController = new \general\controllers\UsuarioController();
            for($i = 0;$i < count($result) ; $i++){
                
                if ($result[$i]["id_usuario_princ"] == $usuarioId){
                    $lista[$i]["usuario"] = $userController->retornarPorId($result[$i]["id_user_seguidor"]);
                }else {
                    $lista[$i]["usuario"] = $userController->retornarPorId($result[$i]["id_usuario_princ"]);
                }

            }
            
            return $lista;

        }else{
            return $result;
        }
    }

    public function fezSolicitacao($usuarioLogado, $usuarioPerfil)
    {
        $stmt = $this->PDO->prepare("SELECT id FROM ".
        $this->tabela .
        " WHERE ( id_usuario_princ = :usuariologado and id_user_seguidor = :usuarioperfil".
        " OR id_usuario_princ = :usuarioperfil and id_user_seguidor = :usuariologado ) AND status_relacionamento = 1");
        $stmt->bindValue(':usuariologado', $usuarioLogado);
        $stmt->bindValue(':usuarioperfil', $usuarioPerfil);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) > 0){
            return true;
        }else {
            return false;
        }

    }

    public function ehAmigo($usuarioLogado,$usuarioPerfil){
        $stmt = $this->PDO->prepare("SELECT id FROM ".
        $this->tabela .
        " WHERE ( id_usuario_princ = :usuariologado and id_user_seguidor = :usuarioperfil".
        " OR id_usuario_princ = :usuarioperfil and id_user_seguidor = :usuariologado ) AND status_relacionamento = 2");
        $stmt->bindValue(':usuariologado', $usuarioLogado);
        $stmt->bindValue(':usuarioperfil', $usuarioPerfil);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) > 0){
            return true;
        }else {
            return false;
        }
    }

    public function naoEhAmigo($usuarioLogado,$usuarioPerfil){
        $stmt = $this->PDO->prepare("SELECT id FROM ".
        $this->tabela .
        " WHERE ( id_usuario_princ = :usuariologado and id_user_seguidor = :usuarioperfil".
        " OR id_usuario_princ = :usuarioperfil and id_user_seguidor = :usuariologado ) AND status_relacionamento = 2");
        $stmt->bindValue(':usuariologado', $usuarioLogado);
        $stmt->bindValue(':usuarioperfil', $usuarioPerfil);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) > 0){
            return false;
        }else {
            return true;
        }
    }
}
