<?php

namespace general\controllers;

use general\helpers\Conexao;

class UsuarioApi
{

    private $PDO;
    private $tabela = "usuario";
    
    function __construct()
    {
        $conexao = new Conexao();
        $this->PDO = $conexao->retornaConexao();
    }

    public function retornaTodos()
    {
        $statement = $this->PDO->query(
            'SELECT id,token,nome,usuario,email,
            sexo,descricao,data_insert,
            caminho_imagem,tipo_usuario FROM usuario WHERE status_usuario = 1'
            );

        return $this->processa_resultado($statement);
    }

    public function retornaUsuarioDesativado($email)
    {
        $usuario = null;
        $stmt = $this->PDO->prepare("SELECT * FROM ".
            $this->tabela.
            " WHERE email = :email and status_usuario = 0");
        
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($result != null) {
            $usuario = $result;
        }
        return $usuario;
    }

    public function retornaUsuarioPorEmail($email)
    {
        $usuario = null;
        $stmt = $this->PDO->prepare("SELECT id,token,email,senha FROM ".
            $this->tabela.
            " WHERE email = :email and status_usuario = 1");
        
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($result != null) {
            $usuario = $result;
        }
        return $usuario;
    }

    public function retornaUsuarioPorLogin($user)
    {
        $usuario = null;
        $stmt = $this->PDO->prepare("SELECT id,token,login,senha FROM ".
            $this->tabela.
            " WHERE usuario = :user and status_usuario = 1");

        $stmt->bindValue(':user', $user);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($result != null) {
            $usuario = $result;
        }
        return $usuario;
    }

    public function retornarPorId($id)
    {

        $usuario = null;
        $stmt = $this->PDO->prepare("SELECT id,token,nome,usuario,email,
            sexo,descricao,data_insert,caminho_imagem,tipo_usuario" .
            " FROM ".
            $this->tabela.
            " WHERE id = :id and status_usuario = 1");

        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($result != null) {
            $usuario = $result;
        }
        return $usuario;
    }

    public function retornaPorToken($token){
        $usuario = null;
        $stmt = $this->PDO->prepare("SELECT id,token,nome,usuario,email,
            sexo,descricao,data_insert,
            caminho_imagem,tipo_usuario FROM ".
            $this->tabela.
            " WHERE token = :token and status_usuario = 1");

        $stmt->bindValue(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($result != null) {
            $usuario = $result;
        }
        return $usuario;
    }

    public function desativar($id)
    {
        $this->desfazCurtidas($id);
        $this->desfazRelacionamentos($id);
        
        try {
            $stmt = $this->PDO->prepare("UPDATE usuario SET ".
                "status_usuario = 0".
                " WHERE id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function ativar($id)
    {
        $this->refazCurtidas($id);  
        try {
            $stmt = $this->PDO->prepare("UPDATE usuario SET ".
                "status_usuario = 1".
                " WHERE id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function novo($dados)
    {
        $valido = $this->verificaDados($dados);
        if ($valido["status"] == 1){
            try {
                $stmt = $this->PDO->prepare("INSERT INTO ".
                    $this->tabela .
                    " (nome,usuario,email,senha,sexo,descricao,token)".
                    "VALUES (:nome,:usuario,:email,:senha,:sexo,:descricao,:token)");

                $stmt->bindValue(':nome', $dados["nome"]);
                $stmt->bindValue(":usuario", $dados["user"]);
                $stmt->bindValue(":email", $dados["email"]);
                $stmt->bindValue(":senha", md5($dados["senha"]));
                $stmt->bindValue(":sexo", $dados["sexo"]);
                $stmt->bindValue(":descricao", $dados["descricao"]);
                $stmt->bindValue(":token",$this->createToken());
                $stmt->execute();
                if ($stmt->rowCount()){
                    $valido["mensagem"] = "Cadastro realizado com sucesso";
                }else{
                    $valido["mensagem"] = "Erro ao cadastrar";
                }
                return $valido;
            } catch (Exception $ex) {
                $this->PDO->rollback();
                $valido["mensagem"] = "Erro ao cadastrar";
                $valido["status"] = 0;
                return $valido;
            }
        }else{
            return $valido;
        }
        
    }

    public function editar($idUsuario,$dados)
    {
        if ($idUsuario > 0){

            if ($dados["senha"] == ""){
              $dados["senha"] = $this->getSenha($idUsuario);
              $dados["repitasenha"] = $this->getSenha($idUsuario);
          }else{
            $dados["senha"] = md5($dados["senha"]);
            $dados["repitasenha"] = md5($dados["repitasenha"]);
        }

        $valido = $this->verificaDados($dados);
        if ($valido["status"] == 1){

            try {
                $stmt = $this->PDO->prepare("UPDATE usuario SET ".
                    "nome = :nome, email = :email,sexo = :sexo".
                    ", senha = :senha,descricao = :descricao".
                    ", data_update = :data_update".
                    ", tipo_usuario = :tipo_usuario".
                    " WHERE id = :id");

                $stmt->bindValue(':nome', $dados["nome"]);
                $stmt->bindValue(":email", $dados["email"]);
                $stmt->bindValue(":senha", $dados["senha"]);
                $stmt->bindValue(":sexo", $dados["sexo"]);
                $stmt->bindValue(":descricao", $dados["descricao"]);
                $stmt->bindValue(":data_update", date("Y-m-d H:i:s"));
                $stmt->bindValue(":tipo_usuario" , $dados["tipousuario"]);
                $stmt->bindValue(":id", $idUsuario);
                $stmt->execute();
                if ($stmt->rowCount()){
                    $valido["mensagem"] = "Usuário editado com sucesso";
                }else{
                    $valido["mensagem"] = "Erro ao editar usuário";
                }
                return $valido;
            } catch (Exception $ex) {
                $this->PDO->rollback();
                $valido["mensagem"] = "Erro ao editar usuário";
                $valido["status"] = 0;
                return $valido;
            }

        }else{
            return $valido;
        }

    }else{
        $valido["mensagem"] = "Erro ao pesquisar usuário";
        $valido["status"] = 0;
        return $valido;
    }

}

public function salvaImagePerfil($caminhoImagem, $id)
{

    try {
        $stmt = $this->PDO->prepare("UPDATE usuario SET ".
            "caminho_imagem = :caminho_imagem".
            ", data_update = :data_update".
            " WHERE id = :id");
        $stmt->bindValue(":caminho_imagem", $caminhoImagem);
        $stmt->bindValue(":data_update", date("Y-m-d H:i:s"));
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    } catch (Exception $ex) {
        $this->PDO->rollback();
    }
}

    //Funções Privadas

private function desfazRelacionamentos($idUsuario){
    try{
        $stmt = $this->PDO->prepare("UPDATE relacionamento SET ".
            "status_relacionamento = 0 WHERE id_usuario_princ  = :idusuario ".
            " OR id_user_seguidor = :idusuario");
        $stmt->bindValue(':idusuario',$idUsuario);
        $stmt->execute();

    }catch (Exception $ex) {
        $this->PDO->rollback();
    }

}

private function desfazCurtidas($idUsuario){
    try{
        $stmt =$this->PDO->prepare("UPDATE curtidas SET ".
            "status_curtida = 0".
            " WHERE id_usuario = :idusuario");
        $stmt->bindValue(':idusuario', $idUsuario);
        $stmt->execute();
    }catch (Exception $ex) {
        $this->PDO->rollback();
    }
}

private function refazCurtidas($idUsuario){
    try{
        $stmt =$this->PDO->prepare("UPDATE curtidas SET ".
            "status_curtida = 1 ".
            " WHERE id_usuario = :idusuario");
        $stmt->bindValue(':idusuario', $idUsuario);
        $stmt->execute();
    }catch (Exception $ex) {
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

private function createToken(){
        //String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
    $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $return= "";

    for($count= 0; 20 > $count; $count++){
            //Gera um caracter aleatorio
        $return.= $basic[rand(0, strlen($basic) - 1)];
    }

    return $return;
}

private function verificaDados($dados){

    $retorno = [];
    $nome = isset($dados["nome"]) ? $dados["nome"] : "" ;
    $user = isset($dados["user"]) ? $dados["user"] : "";
    $sexo = isset($dados["sexo"]) ? $dados["sexo"] : -1;
    $descricao = isset($dados["descricao"]) ? $dados["descricao"] : "";
    $email = isset($dados["email"]) ? strtolower($dados["email"]) : "";
    $senha = isset($dados["senha"]) ? $dados["senha"] : "";
    $repitaSenha = isset($dados["repitasenha"]) ? $dados["repitasenha"] : "";
    if (empty($nome)){
        $retorno["mensagem"] = "Digite algo para o nome";
        $retorno["status"] = 0;
    }else{
        if (empty($user)){
            $retorno["mensagem"] = "Digite algo para o usuário";
            $retorno["status"] = 0;
        }else{
            if ($sexo < 0 || $sexo > 1){
                $retorno["mensagem"] = "Selecione o sexo";
                $retorno["status"] = 0;
            }else{
                if (!preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $email)){
                    $retorno["mensagem"] = "Digite um e-mail válido";
                    $retorno["status"] = 0;
                }else{
                    if (empty($senha)){
                        $retorno["mensagem"] = "Você precisa digitar sua senha";
                        $retorno["status"] = 0;
                    }else{
                        if (empty($repitaSenha)){
                            $retorno["mensagem"] = "Você precisa digitar sua senha novamente da mesma forma";
                            $retorno["status"] = 0;
                        }else{
                            if (strcmp($senha, $repitaSenha) == 0) {
                                $retorno["mensagem"] = "Dados válidos";
                                $retorno["status"] = 1;
                            }else{
                                $retorno["mensagem"] = "As senhas não coincidem";
                                $retorno["status"] = 0;
                            }
                        }
                    }

                }
            }
        }
    }
    return $retorno;

}

private function getSenha($idUsuario){
    $stmt = $this->PDO->prepare("SELECT senha FROM ".
        $this->tabela .
        " WHERE id = :idusuario AND status_usuario = 1");
    $stmt->bindValue(':idusuario',$idUsuario);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_OBJ);
    return $result->senha;
}
}