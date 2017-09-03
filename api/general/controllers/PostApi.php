<?php

namespace general\controllers;

use general\helpers\Conexao;

class PostApi

{

	private $PDO;
	private $tabela = "post";
	private $caminhoLocal;
	private $caminhoImagemDefault = "/webfiles/images/back-post.png";
	
	function __construct($baseUrl = null)
    {
        $conexao = new Conexao();
		$this->PDO = $conexao->retornaConexao();
		$this->caminhoLocal = $baseUrl;
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
			if ($post->caminho_imagem != null){
				$caminho = $post->caminho_imagem;
				$post->caminho_imagem = $this->caminhoLocal . $caminho;
			}else{
				$post->caminho_imagem = $this->caminhoLocal . $this->caminhoImagemDefault;
			}
			
		}
		return $post;
	}

	public function retornaQuantidadePorUsuario($idUsuario)
	{
		$statement = $this->PDO->query(
			'SELECT * FROM post WHERE status_post = 1 and id_usuario = ' .
			$idUsuario .
			' ORDER BY id DESC LIMIT 10 '
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
			' ORDER BY post.data_insert DESC LIMIT 10'
			);
		return $this->processa_resultado($statement);
	}

	public function novo($dados)
	{
		$valido = $this->verificaDados($dados);
		if ($valido["status"] == 1){
			try {
				$stmt = $this->PDO->prepare("INSERT INTO ".
					$this->tabela.
					" (titulo,texto,id_usuario,caminho_imagem) ".
					"VALUES (:titulo,:texto,:id_usuario,:caminho_imagem)");

				$stmt->bindValue(':titulo', $dados["titulo"]);
				$stmt->bindValue(':texto', $dados["texto"]);
				$stmt->bindValue(':id_usuario', $dados["idusuario"]);
				$stmt->bindValue(':caminho_imagem', $dados["caminhoimagem"]);
				$stmt->execute();
				if ($stmt->rowCount()){
					$valido["mensagem"] = "Postado com sucesso";
				}else{
					$valido["mensagem"] = "Erro ao postar";
				}
				return $valido;
			} catch (Exception $ex) {
				$this->PDO->rollback();
				$valido["mensagem"] = "Erro ao postar";
				$valido["status"] = 0;
				return $valido;
			}
		}else {
			return $valido;
		}
	}

	public function editar($idPost,$dados)
	{
		if ($idPost > 0){

			$valido = $this->verificaDados($dados);
			if ($valido["status"] == 1){
				if ($dados["caminhoimagem"] == ""){
					$dados["caminhoimagem"] = $this->getCaminhoImagem($idPost);
				}
				try {

					$stmt = $this->PDO->prepare("UPDATE post SET ".
						" titulo = :titulo, texto = :texto".
						", caminho_imagem = :caminhoimagem, data_update = :dataupdate".
						" WHERE id = :idpost");
					$stmt->bindValue(':titulo', $dados["titulo"]);
					$stmt->bindValue(':texto', $dados["texto"]);
					$stmt->bindValue(':caminhoimagem', $dados["caminhoimagem"]);
					$stmt->bindValue(':dataupdate', date("Y-m-d H:i:s"));
					$stmt->bindValue(':idpost', $idPost);
					$stmt->execute();
					if ($stmt->rowCount()){
						$valido["mensagem"] = "Postado com sucesso";
					}else{
						$valido["mensagem"] = "Erro ao postar";
					}
					return $valido;
				} catch (Exception $ex) {
					$this->PDO->rollback();
					$valido["mensagem"] = "Erro ao editar post";
					$valido["status"] = 0;
					return $valido;
				}
			}else {
				return $valido;
			}
		}else {
			$valido["mensagem"] = "Erro ao editar post";
			$valido["status"] = 0;
			return $valido;
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
				if ($row->caminho_imagem != null){
					$caminho = $row->caminho_imagem;
					$row->caminho_imagem = $this->caminhoLocal . $caminho;
				}else{
					$row->caminho_imagem = $this->caminhoLocal . $this->caminhoImagemDefault;
				}
				
				$results[] = $row;
			}
		}

		return $results;
	}

	private function verificaDados($dados){

		$retorno = [];
		$titulo = isset($dados["titulo"]) ? $dados["titulo"] : "" ;
		$texto = isset($dados["texto"]) ? $dados["texto"] : "";
		$idUsuario = isset($dados["idusuario"]) ? $dados["idusuario"] : -1;
		if (empty($titulo)){
			$retorno["mensagem"] = "Digite algo para o titulo";
			$retorno["status"] = 0;
		}else{
			if (empty($texto)){
				$retorno["mensagem"] = "Digite algo para o texto";
				$retorno["status"] = 0;
			}else{
				if ($idUsuario <= 0){
					$retorno["mensagem"] = "Erro ao encontrar o usuário";
					$retorno["status"] = 0;
				}else{
					$retorno["mensagem"] = "Dados válidos";
					$retorno["status"] = 1;
				}
			}
		}
		return $retorno;

	}

	private function getCaminhoImagem($idPost){
		$stmt = $this->PDO->prepare("SELECT caminho_imagem FROM ".
			$this->tabela .
			" WHERE id = :idpost and status_post = 1");
		$stmt->bindValue(':idpost',$idPost);
		$stmt->execute();
		$result = $stmt->fetch(\PDO::FETCH_OBJ);
		return $result->caminho_imagem;
	}
}
