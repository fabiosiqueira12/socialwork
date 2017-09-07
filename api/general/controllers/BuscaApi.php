<?php

namespace general\controllers;

use general\helpers\Conexao;

class BuscaApi
{
    private $PDO;
    private $caminhoLocal;
    private $imgUserDefault = "/webfiles/images/perfil.png";
    private $imgPostDefault = "/webfiles/images/back-post.png";
    
    function __construct($baseUrl = null)
    {
        $conexao = new Conexao();
        $this->PDO = $conexao->retornaConexao();
        $this->caminhoLocal = $baseUrl;
    }

    public function retornaPessoas($query)
    {

        $stmt = $this->PDO->query(
            "SELECT id,token,nome,caminho_imagem,usuario,email FROM usuario WHERE nome LIKE '%".$query."%'"
        );
        
        $results = array();
 
        if ($stmt) {
            while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
                if ($row->caminho_imagem != null){
                    $caminho = $row->caminho_imagem;
                    $row->caminho_imagem = $this->caminhoLocal . $caminho;
                }else{
                    $row->caminho_imagem = $this->caminhoLocal . $imgUserDefault;
                }
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
            post.caminho_imagem as post_caminho_imagem,
            post.data_insert as post_data
            FROM post INNER JOIN usuario
            WHERE usuario.id = post.id_usuario AND 
            post.titulo LIKE '%" . $query . "%'");

        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
        foreach ($result as $key => $value) {
            if ($value->post_caminho_imagem != null){
                $caminho = $value->post_caminho_imagem;
                $value->post_caminho_imagem = $this->caminhoLocal . $caminho;
            }else{
                $value->post_caminho_imagem = $this->caminhoLocal . $this->imgPostDefault;
            }
            $value->post_data = date( 'd/m/Y' , strtotime($value->post_data)) . " às " . date('h:m',strtotime($value->post_data));;
        }
        return $result;
    }
}
