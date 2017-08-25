<?php

namespace general\helpers;


class Conexao{

	private $PDO;
	private $dns = 'mysql:host=localhost;port=3306;dbname=social_work;charset=utf8';
	private $user = 'root';
	private $pass = '';

	function __construct()
	{
		$this->PDO = new \PDO($this->dns,$this->user,$this->pass); //Conexão
		$this->PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
	}

	function retornaConexao(){
		return $this->PDO;	
	}
	
}

?>