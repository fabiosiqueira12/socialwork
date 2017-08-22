<?php

namespace general\controllers;

class UsuarioApi
{

    private $PDO;
    private $tabela = "usuario";
    
    function __construct()
    {
        $this->PDO = new \PDO('mysql:host=localhost;dbname=social_work;charset=utf8', 'root', ''); //Conexão
        $this->PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
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
        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function novo($usuario)
    {

        try {
            $stmt = $this->PDO->prepare("INSERT INTO ".
                $this->tabela .
                "(nome,usuario,email,senha,sexo,descricao,token)".
                "VALUES (:nome,:usuario,:email,:senha,:sexo,:descricao,:token)");

            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(":usuario", $usuario->getUser());
            $stmt->bindValue(":email", $usuario->getEmail());
            $stmt->bindValue(":senha", $usuario->getSenha());
            $stmt->bindValue(":sexo", $usuario->getSexo());
            $stmt->bindValue(":descricao", $usuario->getDescricao());
            $stmt->bindValue(":token",$this->createToken());
            $stmt->execute();
        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
    }

    public function editar($usuario)
    {
        try {
            $stmt = $this->PDO->prepare("UPDATE usuario SET ".
                "nome = :nome, email = :email,sexo = :sexo".
                ", senha = :senha,descricao = :descricao".
                ", data_update = :data_update".
                ", tipo_usuario = :tipo_usuario".
                " WHERE id = :id");

            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(":email", $usuario->getEmail());
            $stmt->bindValue(":senha", $usuario->getSenha());
            $stmt->bindValue(":sexo", $usuario->getSexo());
            $stmt->bindValue(":descricao", $usuario->getDescricao());
            $stmt->bindValue(":data_update", date("Y-m-d H:i:s"));
            $stmt->bindValue(":tipo_usuario" , $usuario->getTipo());
            $stmt->bindValue(":id", $usuario->getId());
            $stmt->execute();
        } catch (Exception $ex) {
            $this->PDO->rollback();
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
}
