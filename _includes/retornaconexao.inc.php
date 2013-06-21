<?php 
	require_once ('_classes/_DAO/SimpleFactoryDAOBanco.class.php');
	
	session_start();
	
	if (!isset($_SESSION[BANCO_SESSAO]))
	{
		$fabrica = new SimpleFactoryDAOBanco();
		$banco   = $fabrica->criaInstanciaBanco(PRODUTO_BANCO_DE_DADOS, HOST_BANCO, LOGIN_BANCO, SENHA_BANCO, NOME_BANCO);
		
		$_SESSION[BANCO_SESSAO] = $banco;
	}
?>