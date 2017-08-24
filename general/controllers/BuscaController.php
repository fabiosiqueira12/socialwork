<?php

namespace general\controllers;

use general\helpers\Conexao;

class BuscaController
{
    private $PDO;
    
    function __construct()
    {
        $conexao = new Conexao();
        $this->PDO = $conexao->retornaConexao();
    }
    public function retornaPessoas($query)
    {

        $stmt = $this->PDO->query(
            "SELECT * FROM usuario WHERE nome LIKE '%".$query."%'"
        );
        
        $results = array();
 
        if ($stmt) {
            while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
                $usuario = new \general\models\Usuario();
                $usuario->setId($row->id);
                $usuario->setNome($row->nome);
                $usuario->setUser($row->usuario);
                $usuario->setEmail($row->email);
                $usuario->setSenha($row->senha);
                $usuario->setSexo($row->sexo);
                $usuario->setDescricao($row->descricao);
                $usuario->setCaminhoImagem($row->caminho_imagem);
                $results[] = $usuario;
            }
        }
        return $results;
    }

    public function retornaPosts($query)
    {
        $stmt = $this->PDO->query(
            "SELECT * FROM post WHERE titulo LIKE '%".$query."%' OR texto LIKE '%".$query."%'"
        );

        $results = array();

        if ($stmt) {
            $usuarioController = new \general\controllers\UsuarioController();
            while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
                $post = new \general\models\Post();
                $post->setId($row->id);
                $post->setTitulo($row->titulo);
                $post->setTexto($row->texto);
                $post->setUsuario($usuarioController->retornarPorId($row->id_usuario));
                $post->setCaminhoImagem($row->caminho_imagem);
                $post->setDataCriacao($row->data_insert);
                $results[] = $post;
            }
        }
        return $results;
    }
}
