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

    }

    public function retornaPorId($id){

    }

    public function adicionarLocalizacao($idLocal,$idUsuario){

    }

    public function editarLocalizacao($idLocal){

    }

    public function removerLocalizacao($idLocal){
    	
    }

    private function verificaUsuario($idUsuario){
    	$existe = false;
    	$usuarioController = new UsuarioApi();

    	if ($usuarioController->retornarPorId($idUsuario) != null){
    		$existe = true;
    	}

    	return $existe;

    }

}
