<?php

namespace general\helpers;


class Conexao{

	private $PDO;

	function __construct()
	{
		$this->PDO = new \PDO('mysql:host=localhost;dbname=social_work;charset=utf8', 'root', ''); //Conexão
		$this->PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
	}

	function retornaConexao(){
		return $this->PDO;
	}
	
}

?>