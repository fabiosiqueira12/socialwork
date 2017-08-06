<?php

namespace general\models;

class Post{
    
    private $id;
    private $titulo;
    private $texto;
    private $usuario;
    private $dataCriacao;
    private $caminhoImagem;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setTitulo($titulo){
        $this->titulo = $titulo;
    }

    public function getTitulo(){
        return $this->titulo;
    }

    public function setTexto($texto){
        $this->texto = $texto;
    }

    public function getTexto(){
        return $this->texto;
    }

    public function setUsuario($usuario){
        $this->usuario = $usuario;
    }

    public function getUsuario(){
        return $this->usuario;
    }

    public function setDataCriacao($dataCriacao){
        $this->dataCriacao = $dataCriacao;
    }

    public function getDataCriacao(){
        return $this->dataCriacao;
    }

    public function dataFormatada(){
        return date( 'd/m/Y' , strtotime($this->dataCriacao));
    }

    public function horaPublicacao(){
        return date('h:m',strtotime($this->dataCriacao));
    }

    public function setCaminhoImagem($caminhoImagem){
        $this->caminhoImagem = $caminhoImagem;
    }

    public function getCaminhoImagem(){
        return $this->caminhoImagem;
    }

}