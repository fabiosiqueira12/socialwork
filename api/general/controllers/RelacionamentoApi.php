<?php

namespace general\controllers;

use general\helpers\Conexao;

class RelacionamentoApi
{
    private $PDO;
    private $tabela = "relacionamento";
    private $caminhoLocal;
    private $imgUserDefault = "/webfiles/images/perfil.png";
    
    function __construct($baseUrl = null)
    {
        $conexao = new Conexao();
        $this->PDO = $conexao->retornaConexao();
        $this->caminhoLocal = $baseUrl;
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
        " usuario.usuario as login, relacionamento.data_solicitacao as data_solicitacao, " .
        " usuario.nome as nome_usuario, ".
        " relacionamento.id as relacionamento_id ".
        " FROM relacionamento INNER JOIN usuario".
        " WHERE usuario.id != :usuarioid ".
        " AND (relacionamento.id_usuario_princ = usuario.id OR relacionamento.id_user_seguidor = usuario.id) ".
        " AND (relacionamento.id_usuario_princ = :usuarioid OR relacionamento.id_user_seguidor = :usuarioid) ".
        " AND relacionamento.status_relacionamento = 1 AND usuario.status_usuario = 1"
        );
        $stmt->bindValue(':usuarioid',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
        foreach ($result as $key => $value) {
            if ($value->imagem_usuario != null){
                $caminho = $value->imagem_usuario;
                $value->imagem_usuario = $this->caminhoLocal . $caminho;
            }else{
                $value->imagem_usuario = $this->caminhoLocal . $this->imgUserDefault;
            }
            $value->data_solicitacao = date( 'd/m/Y' , strtotime($value->data_solicitacao)) . " às " . date('h:m',strtotime($value->data_solicitacao));;
        }

        return $result;
    }

    public function retornaAmigos($usuarioId)
    {
        $stmt = $this->PDO->prepare("SELECT usuario.id as id_usuario,usuario.caminho_imagem as imagem_usuario, ".
        " usuario.usuario as login, relacionamento.data_solicitacao as data_solicitacao, " .
        " usuario.nome as nome_usuario, ".
        " usuario.descricao as descricao_usuario "
        " usuario.email as email_usuario".
        " FROM relacionamento INNER JOIN usuario".
        " WHERE usuario.id != :usuarioid ".
        " AND (relacionamento.id_usuario_princ = usuario.id OR relacionamento.id_user_seguidor = usuario.id) ".
        " AND (relacionamento.id_usuario_princ = :usuarioid OR relacionamento.id_user_seguidor = :usuarioid) ".
        " AND relacionamento.status_relacionamento = 2 AND usuario.status_usuario = 1"
        );
        $stmt->bindValue(':usuarioid',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
        foreach ($result as $key => $value) {
            if ($value->imagem_usuario != null){
                $caminho = $value->imagem_usuario;
                $value->imagem_usuario = $this->caminhoLocal . $caminho;
            }else{
                $value->imagem_usuario = $this->caminhoLocal . $this->imgUserDefault;
            }
        }
        return $result;
    }

    public function retornaQuantAmigos($usuarioId)
    {
        $stmt = $this->PDO->prepare("SELECT relacionamento.id as id ".
        " FROM relacionamento INNER JOIN usuario".
        " WHERE usuario.id != :usuarioid ".
        " AND (relacionamento.id_usuario_princ = usuario.id OR relacionamento.id_user_seguidor = usuario.id) ".
        " AND (relacionamento.id_usuario_princ = :usuarioid OR relacionamento.id_user_seguidor = :usuarioid) ".
        " AND relacionamento.status_relacionamento = 2 AND usuario.status_usuario = 1"
        );
        $stmt->bindValue(':usuarioid',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $quantidade = count($result);
        return $quantidade;
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
