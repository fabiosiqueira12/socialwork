<?php

namespace general\controllers;

use general\helpers\Conexao;
use general\helpers\UserControl;

class UsuarioController
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
            'SELECT * FROM usuario WHERE status_usuario = 1'
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
            $usuario = new \general\models\Usuario();
            $usuario->setNome($result->nome);
            $usuario->setUser($result->usuario);
            $usuario->setEmail($result->email);
            $usuario->setSenha($result->senha);
            $usuario->setDescricao($result->descricao);
            $usuario->setSexo($result->sexo);
            $usuario->setId($result->id);
        }
        return $usuario;
    }

    public function retornaUsuarioPorEmail($email)
    {
        $usuario = null;
        $stmt = $this->PDO->prepare("SELECT * FROM ".
        $this->tabela.
        " WHERE email = :email and status_usuario = 1");
        
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($result != null) {
            $usuario = new \general\models\Usuario();
            $usuario->setNome($result->nome);
            $usuario->setUser($result->usuario);
            $usuario->setEmail($result->email);
            $usuario->setSenha($result->senha);
            $usuario->setDescricao($result->descricao);
            $usuario->setSexo($result->sexo);
            $usuario->setId($result->id);
            $usuario->setToken($result->token);
            $usuario->setTipo($result->tipo_usuario);
            $usuario->setCaminhoImagem($result->caminho_imagem);
        }
        return $usuario;
    }

    public function retornaUsuarioPorLogin($user)
    {
        $usuario = null;
        $stmt = $this->PDO->prepare("SELECT * FROM ".
        $this->tabela.
        " WHERE usuario = :user and status_usuario = 1");

        $stmt->bindValue(':user', $user);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($result != null) {
            $usuario = new \general\models\Usuario();
            $usuario->setNome($result->nome);
            $usuario->setUser($result->usuario);
            $usuario->setEmail($result->email);
            $usuario->setSenha($result->senha);
            $usuario->setDescricao($result->descricao);
            $usuario->setSexo($result->sexo);
            $usuario->setId($result->id);
            $usuario->setToken($result->token);
            $usuario->setTipo($result->tipo_usuario);
            $usuario->setCaminhoImagem($result->caminho_imagem);
        }
        return $usuario;
    }

    public function retornarPorId($id)
    {

        $usuario = null;
        $stmt = $this->PDO->prepare("SELECT * FROM ".
        $this->tabela.
        " WHERE id = :id and status_usuario = 1");

        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($result != null) {
            $usuario = new \general\models\Usuario();
            $usuario->setNome($result->nome);
            $usuario->setUser($result->usuario);
            $usuario->setEmail($result->email);
            $usuario->setSenha($result->senha);
            $usuario->setDescricao($result->descricao);
            $usuario->setSexo($result->sexo);
            $usuario->setId($result->id);
            $usuario->setToken($result->token);
            $usuario->setTipo($result->tipo_usuario);
            $usuario->setCaminhoImagem($result->caminho_imagem);
        }
        return $usuario;
    }

    public function retornaPorToken($token){
        $usuario = null;
        $stmt = $this->PDO->prepare("SELECT * FROM ".
        $this->tabela.
        " WHERE token = :token and status_usuario = 1");

        $stmt->bindValue(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        if ($result != null) {
            $usuario = new \general\models\Usuario();
            $usuario->setNome($result->nome);
            $usuario->setUser($result->usuario);
            $usuario->setEmail($result->email);
            $usuario->setSenha($result->senha);
            $usuario->setDescricao($result->descricao);
            $usuario->setSexo($result->sexo);
            $usuario->setId($result->id);
            $usuario->setToken($result->token);
            $usuario->setTipo($result->tipo_usuario);
            $usuario->setCaminhoImagem($result->caminho_imagem);
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
            $userControl = new UserControl();
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
            $stmt->bindValue(":token",$userControl->createToken());
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
                $usuario = new \general\models\Usuario();
                $usuario->setId($row->id);
                $usuario->setToken($row->token);
                $usuario->setNome($row->nome);
                $usuario->setUser($row->usuario);
                $usuario->setEmail($row->email);
                $usuario->setSenha($row->senha);
                $usuario->setSexo($row->sexo);
                $usuario->setDescricao($row->descricao);
                $usuario->setCaminhoImagem($row->caminho_imagem);
                $usuario->setTipo($row->tipo_usuario);
                $results[] = $usuario;
            }
        }
    
            return $results;
    }
}
