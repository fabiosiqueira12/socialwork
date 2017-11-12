<?php

namespace general\helpers;


class Conexao{

	private $PDO;
	private $dns = 'mysql:host=sql10.freemysqlhosting.net;port=3306;dbname=sql10204519;charset=utf8';
	private $user = 'sql10204519';
	private $pass = '2vR8RLJB5s';

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