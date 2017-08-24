<?php

namespace general\controllers;

use general\controllers\UsuarioController;
use general\helpers\Conexao;

class PostController
{

	private $PDO;
	private $tabela = "post";
	
	function __construct()
    {
        $conexao = new Conexao();
        $this->PDO = $conexao->retornaConexao();
    }

	public function retornaTodos()
	{
		$statement = $this->PDO->query(
			'SELECT * FROM post WHERE status_post = 1'
			);

		return $this->processa_resultado($statement);
	}

	public function retornaPorId($id)
	{
		
		$post = null;
		$stmt = $this->PDO->prepare("SELECT * FROM ".
			$this->tabela.
			" WHERE id = :id and status_post = 1");
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		$result = $stmt->fetch(\PDO::FETCH_OBJ);
		if ($result != null) {
			$usuarioController = new \general\controllers\UsuarioController();
			$post = new \general\models\Post();
			$post->setId($result->id);
			$post->setTitulo($result->titulo);
			$post->setTexto($result->texto);
			$post->setUsuario($usuarioController->retornarPorId($result->id_usuario));
			$post->setCaminhoImagem($result->caminho_imagem);
			$post->setDataCriacao($result->data_insert);
		}
		return $post;
	}

	public function retornaQuantidadePorUsuario($idUsuario)
	{
		$usuarioController = new \general\controllers\UsuarioController();
		$posts = [];
		
		$stmt = $this->PDO->prepare("SELECT * FROM ".
			$this->tabela.
			" WHERE id_usuario = :idusuario and status_post = 1" .
			" ORDER BY id DESC LIMIT 20");
		$stmt->bindValue(':idusuario', $idUsuario);
		$stmt->execute();
		$result = $stmt->fetchALL();

		for ($i = 0; $i < count($result); $i++) {
			$post = new \general\models\Post();
			$post->setId($result[$i]["id"]);
			$post->setTitulo($result[$i]["titulo"]);
			$post->setTexto($result[$i]["texto"]);
			$post->setDataCriacao($result[$i]["data_insert"]);
			$post->setUsuario($usuarioController->retornarPorId($result[$i]["id_usuario"]));
			$post->setCaminhoImagem($result[$i]["caminho_imagem"]);
			$posts[$i] = $post;
		}
		
		return $posts;
	}

	public function novo($post)
	{
		try {
			$stmt = $this->PDO->prepare("INSERT INTO ".
				$this->tabela.
				" (titulo,texto,id_usuario,caminho_imagem) ".
				"VALUES (:titulo,:texto,:id_usuario,:caminho_imagem)");

			$stmt->bindValue(':titulo', $post->getTitulo());
			$stmt->bindValue(':texto', $post->getTexto());
			$stmt->bindValue(':id_usuario', $post->getUsuario()->getId());
			$stmt->bindValue(':caminho_imagem', $post->getCaminhoImagem());
			$stmt->execute();
		} catch (Exception $ex) {
			$this->PDO->rollback();
		}
	}

	public function editar($post)
	{
		
		try {

			$stmt = $this->PDO->prepare("UPDATE post SET ".
				" titulo = :titulo, texto = :texto".
				", caminho_imagem = :caminhoimagem, data_update = :dataupdate".
				" WHERE id = :idpost");
			$stmt->bindValue(':titulo', $post->getTitulo());
			$stmt->bindValue(':texto', $post->getTexto());
			$stmt->bindValue(':caminhoimagem', $post->getCaminhoImagem());
			$stmt->bindValue(':dataupdate', date("Y-m-d H:i:s"));
			$stmt->bindValue(':idpost', $post->getId());
			$stmt->execute();

		} catch (Exception $ex) {
			$this->PDO->rollback();
		}
	}

	public function excluir($idPost){
		try{
			$stmt = $this->PDO->prepare("UPDATE post SET ".
				"status_post = 0".
				" WHERE id = :idpost");
			$stmt->bindValue(':idpost',$idPost);
			$stmt->execute();

		}catch (Exception $ex) {
			$this->PDO->rollback();
		}
	}

	public function retornaQuantidadeDePosts($usuarioId){
		$stmt = $this->PDO->prepare("SELECT id FROM ".
			$this->tabela.
			" WHERE id_usuario = :idusuario  and status_post = 1");
		$stmt->bindValue(':idusuario',$usuarioId);
		$stmt->execute();
		$result = $stmt->fetchALL();

		return count($result);

	}

	public function retornaPostsDeAmigos($usuarioId){

		$stmt = $this->PDO->prepare("SELECT post.id_usuario as id_usuario, post.id as id_post, post.titulo as titulo_post, post.texto as post_texto, post.data_insert as post_data, post.caminho_imagem as post_imagem FROM ".
			$this->tabela . " INNER JOIN relacionamento ".
			"WHERE post.id_usuario != :usuarioid AND ".
			"(relacionamento.id_usuario_princ = post.id_usuario OR relacionamento.id_user_seguidor = post.id_usuario) " .
			" AND relacionamento.status_relacionamento = 2".
			" AND (relacionamento.id_usuario_princ = :usuarioid OR relacionamento.id_user_seguidor = :usuarioid)".
			" AND post.status_post = 1".
			" ORDER BY post.data_insert DESC LIMIT 20");
		$stmt->bindValue(':usuarioid', $usuarioId);
		$stmt->execute();
		$result = $stmt->fetchAll();

		if (count($result) > 0){
			$usuarioController = new UsuarioController();
			$lista = [];
			for($i = 0;$i < count($result);$i++){

				$post = new \general\models\Post();
				$post->setId($result[$i]["id_post"]);
				$post->setTitulo($result[$i]["titulo_post"]);
				$post->setTexto($result[$i]["post_texto"]);
				$post->setDataCriacao($result[$i]["post_data"]);
				$post->setCaminhoImagem($result[$i]["post_imagem"]);
				$post->setUsuario($usuarioController->retornarPorId($result[$i]["id_usuario"]));
				$lista[] = $post;
			}
			return $lista;
		}else {
			return $result;
		}

	}

	//Funções Privadas
	private function processa_resultado($statement)
	{
		$usuarioController = new \general\controllers\UsuarioController();
		$results = array();

		if ($statement) {
			while ($row = $statement->fetch(\PDO::FETCH_OBJ)) {
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
