<?php 
	require_once ('../_includes/confconexao.inc.php');
	require_once ('../_classes/Loader.class.php');
	
	$fabrica = new SimpleFactoryDAOBanco();
	try {
		$banco = $fabrica->criaInstanciaBanco(PRODUTO_BANCO_DE_DADOS, HOST_BANCO, LOGIN_BANCO, SENHA_BANCO, NOME_BANCO);
		$banco->abreConexao();
		echo "Conexão efetuada com sucesso.";
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	$banco->fechaConexao();
?>