<?php

namespace general\controllers;

use general\helpers\Conexao;

class RelacionamentoApi
{
    private $PDO;
    private $tabela = "relacionamento";
    
    function __construct()
    {
        $conexao = new Conexao();
        $this->PDO = $conexao->retornaConexao();
    }

    public function solicitaAmizade($userPediu, $userRecebeu)
    {
        if ($this->existeRelacionamento($userPediu,$userRecebeu)){
            $valido["mensagem"] = "Erro ao solicitar amizade,já foi feita uma soliticação ou são amigos";
            $valido["status"] = 0;
            return $valido;
        }else{
            try {
                $stmt = $this->PDO->prepare("INSERT INTO ".
                    $this->tabela .
                    "(id_usuario_princ,id_user_seguidor)".
                    " VALUES (:usuarioprinc,:usuarioseguidor)");

                $stmt->bindValue(':usuarioprinc', $userPediu);
                $stmt->bindValue(":usuarioseguidor", $userRecebeu);
                $stmt->execute();
                if ($stmt->rowCount()){
                    $valido["mensagem"] = "Solicitação Realizada com sucesso";
                    $valido["status"] = 1;
                }else{
                    $valido["mensagem"] = "Erro ao solicitar amizade ou solicitação já foi feita";
                    $valido["status"] = 0;
                }
                return $valido;
            } catch (Exception $ex) {
                $this->PDO->rollback();
                $valido["mensagem"] = "Erro ao solicitar amizade";
                $valido["status"] = 0;
                return $valido;
            }
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
            if ($stmt->rowCount()){
                $valido["mensagem"] = "Sucesso ao aceitar soliticação";
                $valido["status"] = 1;
            }else{
                $valido["mensagem"] = "Solicitação já foi aceita";
                $valido["status"] = 0;
            }
            return $valido;
        } catch (Exception $ex) {
            $this->PDO->rollback();
            $valido["mensagem"] = "Erro ao aceitar solicitação";
            $valido["status"] = 0;
            return $valido;
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
            if ($stmt->rowCount()){
                $valido["mensagem"] = "Sucesso ao recusar soliticação";
                $valido["status"] = 1;
            }else{
                $valido["mensagem"] = "Solicitação já foi recusada";
                $valido["status"] = 0;
            }
            return $valido;
        } catch (Exception $ex) {
            $this->PDO->rollback();
            $valido["mensagem"] = "Erro ao recusar solicitação";
            $valido["status"] = 0;
            return $valido;
        }
    }

    public function desfazerAmizade($usuarioLogado,$usuarioPerfil){

        if ($this->ehAmigo($usuarioLogado,$usuarioPerfil)){
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
                $valido["mensagem"] = "Amizade desfeita com sucesso";
                $valido["status"] = 1;
                return $valido;

            }catch (Exception $ex) {
                $this->PDO->rollback();
            }
        }else{
            $valido["mensagem"] = "Erro ao desfazer amizade, não existe amizades entre os dois usuários";
            $valido["status"] = 0;
            return $valido;      
        }
        
    }

    public function retornaSolicitacoes($usuarioId)
    {
        $stmt = $this->PDO->prepare("SELECT usuario.id as id_usuario,usuario.caminho_imagem as imagem_usuario, ".
        " usuario.usuario as login, relacionamento.data_solicitacao " .
        " FROM relacionamento INNER JOIN usuario".
        " WHERE usuario.id != :usuarioid ".
        " AND (relacionamento.id_usuario_princ = usuario.id OR relacionamento.id_user_seguidor = usuario.id) ".
        " AND (relacionamento.id_usuario_princ = :usuarioid OR relacionamento.id_user_seguidor = :usuarioid) ".
        " AND relacionamento.status_relacionamento = 1 AND usuario.status_usuario = 1"
        );
        $stmt->bindValue(':usuarioid',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return $result;
    }

    public function retornaAmigos($usuarioId)
    {
        $stmt = $this->PDO->prepare("SELECT usuario.id as id_usuario,usuario.caminho_imagem as imagem_usuario, ".
        " usuario.usuario as login, relacionamento.data_solicitacao " .
        " FROM relacionamento INNER JOIN usuario".
        " WHERE usuario.id != :usuarioid ".
        " AND (relacionamento.id_usuario_princ = usuario.id OR relacionamento.id_user_seguidor = usuario.id) ".
        " AND (relacionamento.id_usuario_princ = :usuarioid OR relacionamento.id_user_seguidor = :usuarioid) ".
        " AND relacionamento.status_relacionamento = 2 AND usuario.status_usuario = 1"
        );
        $stmt->bindValue(':usuarioid',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return $result;
    }

    private function existeRelacionamento($userPediu, $userRecebeu){
        $stmt = $this->PDO->prepare("SELECT id FROM ".
            $this->tabela .
            " WHERE ( id_usuario_princ = :usuariologado and id_user_seguidor = :usuarioperfil".
            " OR id_usuario_princ = :usuarioperfil and id_user_seguidor = :usuariologado )" . 
            " AND (status_relacionamento = 2 OR status_relacionamento = 1 )");
        $stmt->bindValue(':usuariologado',$userPediu);
        $stmt->bindValue(':usuarioperfil',$userRecebeu);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) > 0){
            return true;
        }else {
            return false;
        }
    }

    private function ehAmigo($userPediu,$userRecebeu){
        $stmt = $this->PDO->prepare("SELECT id FROM ".
            $this->tabela .
            " WHERE ( id_usuario_princ = :usuariologado and id_user_seguidor = :usuarioperfil".
            " OR id_usuario_princ = :usuarioperfil and id_user_seguidor = :usuariologado )" . 
            " AND status_relacionamento = 2 ");
        $stmt->bindValue(':usuariologado',$userPediu);
        $stmt->bindValue(':usuarioperfil',$userRecebeu);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) > 0){
            return true;
        }else {
            return false;
        }

    }
}