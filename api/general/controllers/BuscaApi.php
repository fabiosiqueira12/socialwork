<?php

namespace general\controllers;

use general\helpers\Conexao;

class BuscaApi
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
            "SELECT id,nome,caminho_imagem,usuario,email FROM usuario WHERE nome LIKE '%".$query."%'"
        );
        
        $results = array();
 
        if ($stmt) {
            while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function retornaPosts($query)
    {
        $stmt = $this->PDO->prepare("SELECT usuario.id as id_usuario , usuario.nome as usuario_nome,
            usuario.email as usuario_email, usuario.usuario as login,
            post.id as post_id,post.titulo as post_titulo,post.texto as post_texto,
            post.caminho_imagem as post_caminho_imagem
            FROM post INNER JOIN usuario
            WHERE usuario.id = post.id_usuario AND 
            post.titulo LIKE '%" . $query . "%'");

        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return $result;
    }
}
