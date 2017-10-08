<?php

namespace general\controllers;

use general\controllers\UsuarioApi;
use general\helpers\Conexao;


class LocalizacaoApi{
	private $PDO;
	private $tabela = "localizacao";
	private $caminhoLocal;

	function __construct($baseUrl = null)
	{
		$conexao = new Conexao();
		$this->PDO = $conexao->retornaConexao();
		$this->caminhoLocal = $baseUrl;
	}

	public function retornaTodos(){

		$statement = $this->PDO->query(
			'SELECT * FROM localizacao WHERE status = 1'
		);

		return $this->processa_resultado($statement);

	}

	public function retornaTodosPorUsuario($usuarioId){

        $stmt= $this->PDO->prepare(
            "SELECT * FROM ".
            $this->tabela .
            " WHERE id_usuario = :idusuario  AND status = 1"
            );
        $stmt->bindValue(':idusuario',$usuarioId);
        $stmt->execute();
        $result = $stmt->fetchALL(\PDO::FETCH_OBJ);
        return $result;
	}

	public function retornaPorId($id){

        $stmt= $this->PDO->prepare(
            "SELECT * FROM ".
            $this->tabela .
            " WHERE id = :id  AND status = 1"
            );
        $stmt->bindValue(':id',$id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
    
        return $result;
	}

	public function editarLocalizacao($dados,$idLocal){

			try {
				
				$stmt = $this->PDO->prepare("UPDATE ".
					$this->tabela .
					" SET logradouro = :logradouro, complemento = :complemento, bairro = :bairro, numero = :numero,cidade = :cidade, uf = :uf, cep = :cep, latitude = :latitude, longitude = :longitude".
					" WHERE id = :id");

				$stmt->bindValue(':logradouro', $dados["logradouro"]);
				$stmt->bindValue(':complemento', $dados["complemento"]);
				$stmt->bindValue(':bairro', $dados["bairro"]);
				$stmt->bindValue(':numero', $dados["numero"]);
				$stmt->bindValue(':cidade', $dados["cidade"]);
				$stmt->bindValue(':uf', $dados["uf"]);
				$stmt->bindValue(':cep', $dados["cep"]);
				$stmt->bindValue(':latitude', $dados["latitude"]);
				$stmt->bindValue(':longitude', $dados["longitude"]);
				$stmt->bindValue(':id', $idLocal);
				$stmt->execute();
				if ($stmt->rowCount()){
					$valido["mensagem"] = "Localização editada com sucesso";
					$valido["status"] = 1;
				}else{
					$valido["mensagem"] = "Erro ao editar localização";
					$valido["status"] = 0;
				}
				return $valido;

			} catch (Exception $e) {
				$valida["mensagem"] = "Erro ao cadastrar";
				$valida["status"] = 0;
				return $valida;
			}

	}

	public function adicionarLocalizacao($dados){

		$valida = $this->verificaDados($dados);

		if($valida["status"] == 1){
			try {
				
				$stmt = $this->PDO->prepare("INSERT INTO ".
					$this->tabela.
					" (id_usuario,logradouro,complemento,bairro,numero,cidade,uf,cep,latitude,longitude) ".
					"VALUES (:idusuario,:logradouro,:complemento,:bairro,:numero,:cidade,:uf,:cep,:latitude,:longitude)");

				$stmt->bindValue(':idusuario', $dados["idusuario"]);
				$stmt->bindValue(':logradouro', $dados["logradouro"]);
				$stmt->bindValue(':complemento', $dados["complemento"]);
				$stmt->bindValue(':bairro', $dados["bairro"]);
				$stmt->bindValue(':numero', $dados["numero"]);
				$stmt->bindValue(':cidade', $dados["cidade"]);
				$stmt->bindValue(':uf', $dados["uf"]);
				$stmt->bindValue(':cep', $dados["cep"]);
				$stmt->bindValue(':latitude', $dados["latitude"]);
				$stmt->bindValue(':longitude', $dados["longitude"]);
				$stmt->execute();
				if ($stmt->rowCount()){
					$valido["mensagem"] = "Localização salva com sucesso";
					$valido["status"] = 1;
				}else{
					$valido["mensagem"] = "Erro ao salvar localização";
					$valido["status"] = 0;
				}
				return $valido;

			} catch (Exception $e) {
				$valida["mensagem"] = "Erro ao cadastrar";
				$valida["status"] = 0;
				return $valida;
			}
		}else {
			return $valida;
		}

	}

	public function removerLocalizacao($idLocal){
		try {
            $stmt = $this->PDO->prepare("UPDATE localizacao SET ".
                "status = 0".
                " WHERE id = :id");
            $stmt->bindValue(":id", $idLocal);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        } catch (Exception $ex) {
            $this->PDO->rollback();
        }
	}

	private function verificaUsuario($idUsuario){
		$existe = false;
		$usuarioController = new UsuarioApi();

		if ($usuarioController->retornarPorId($idUsuario) != null){
			$existe = true;
		}

		return $existe;

	}

	private function verificaDados($dados){

		if (empty($dados["cep"])){
			$valida["mensagem"] = "Informe o CEP";
			$valida["status"] = 0;
		}else {
			if (!preg_match('/[0-9]{5,5}([-]?[0-9]{3})?$/', $dados["cep"])){
				$valida["mensagem"] = "CEP Inválido";
				$valida["status"] = 0;
			}else {
				if (empty($dados["logradouro"])){
					$valida["mensagem"] = "Informe o logradouro";
					$valida["status"] = 0;
				}else{

					if(empty($dados["bairro"])){
						$valida["mensagem"] = "Informe o bairro";
						$valida["status"] = 0;
					}else{

						if (empty($dados["numero"])){
							$valida["mensagem"] = "Informe o número";
							$valida["status"] = 0;
						}else {

							if (empty($dados["cidade"])){
								$valida["mensagem"] = "Informa a cidade";
								$valida["status"] = 0;
							}else {

								if (empty($dados["uf"])){
									$valida["mensagem"] = "Infome o estado";
									$valida["status"] = 0;
								}else{

									if (empty($dados["latitude"])){
										$valida["mensagem"] = "Latitude inválida";
										$valida["status"] = 0;
									}else{

										if (empty($dados["longitude"])){
											$valida["mensagem"] = "Longitude inválida";
											$valida["status"] = 0;
										}else {

											if (isset($dados["idusuario"])){

												if ($this->verificaUsuario($dados["idusuario"])){
													$valida["mensagem"] = "Dados Válidos";
													$valida["status"] = 1;
												}else{
													$valida["mensagem"] = "Usuário Inválido";
													$valida["status"] = 1;
												}

											}else{
												$valida["mensagem"] = "Usuário Inválido";
												$valida["status"] = 1;
											}

										}

									}

								}

							}

						}

					}

				}
			}
		}
		return $valida;
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

}
