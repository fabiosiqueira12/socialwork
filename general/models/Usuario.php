<?php

namespace general\models;

class Usuario{
    
    private $id;
    private $nome;
    private $user;
    private $email;
    private $senha;
    private $sexo;
    private $descricao;
    private $caminhoImagem;
    private $tipoUsuario;
    private $token;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setUser($user){
        $this->user = $user;
    }

    public function getUser(){
        return $this->user;
    }

    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setSexo($sexo){
        $this->sexo = $sexo;
    }

    public function getSexo(){
        return $this->sexo;
    }

    public function getSexoDesc(){
        if ($this->getSexo() == 0){
            return "Masculino";
        }else {
            return "Feminino";
        }
    }

    public function setSenha($senha){
        $this->senha = $senha;
    }

    public function getSenha(){
        return $this->senha;
    }

    public function setCaminhoImagem($caminhoImagem){
        $this->caminhoImagem = $caminhoImagem;
    }

    public function getCaminhoImagem(){
        return $this->caminhoImagem;
    }

    public function setTipo($tipoUsuario){
        $this->tipoUsuario = $tipoUsuario;
    }

    public function getTipo(){
        return $this->tipoUsuario;
    }

    public function setToken($token){
        $this->token = $token;
    }

    public function getToken(){
        return $this->token;
    }


}