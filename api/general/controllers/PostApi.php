<?php

namespace general\controllers;

class PostApi

{

	private $PDO;
	private $tabela = "post";
	
	function __construct()
	{
		$this->PDO = new \PDO('mysql:host=localhost;dbname=social_work;charset=utf8', 'root', ''); //Conexão
		$this->PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
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
			$post = $result;
		}
		return $post;
	}

	public function retornaQuantidadePorUsuario($idUsuario)
	{
		$statement = $this->PDO->query(
			'SELECT * FROM post WHERE status_post = 1 and id_usuario = ' .
			$idUsuario .
			' ORDER BY id DESC LIMIT 20 '
			);
		return $this->processa_resultado($statement);
	}


	public function retornaPostsDeAmigos($usuarioId){

		$statement = $this->PDO->query(
			'SELECT post.id_usuario as id_usuario, post.id as id_post, post.titulo as titulo_post, post.texto as post_texto, post.data_insert as post_data, post.caminho_imagem as post_imagem FROM '.
			'post' . ' INNER JOIN relacionamento '.
			'WHERE post.id_usuario != ' . $usuarioId . ' AND '.
			'(relacionamento.id_usuario_princ = post.id_usuario OR relacionamento.id_user_seguidor = post.id_usuario)'.
			' AND relacionamento.status_relacionamento = 2 '.
			' AND (relacionamento.id_usuario_princ = ' . $usuarioId . ' OR relacionamento.id_user_seguidor = ' .
			$usuarioId .' ) '.
			' AND post.status_post = 1'.
			' ORDER BY post.data_insert DESC LIMIT 20'
			);
		return $this->processa_resultado($statement);
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
			return $stmt->rowCount() ? true : false;
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

	

	//Funções Privadas
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

}
