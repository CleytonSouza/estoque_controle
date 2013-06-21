<?php
	$gmtDate = gmdate("D, d M Y H:i:s");
	header("Expires: {$gmtDate} GMT"); 
	header("Last-Modified: {$gmtDate} GMT"); 
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1");
	
	require_once ('_classes/FiltroSQL.class.php');
	require_once ('_classes/_MODEL/Cliente.class.php');
	require_once ('_classes/_HELPER/ClienteHelper.class.php');
	
	require_once ('_includes/confconexao.inc.php');
	require_once ('_includes/retornaconexao.inc.php');
	
	$banco     = $_SESSION[BANCO_SESSAO];
	$clihelper = new ClienteHelper();
	
	$acao  = isset($_POST['acao']) ? $_POST['acao'] : "";
	$cod   = isset($_POST['cod'])  ? $_POST['cod']  : null;
	$nome  = isset($_POST['n'])    ? $_POST['n']    : null;
	
	if(isset($cod))
			$filtro = new FiltroSQL(FiltroSQL::CONECTOR_E, FiltroSQL::OPERADOR_IGUAL, array("codigo" => $cod));
			
	switch($acao)
	{		
		case 'habilitar':
				try {
					$camposValores['habil'] = 1;
					$clihelper->alterar($banco, $camposValores, $filtro);
					echo "habilitou";
				}
				catch (Exception $e) {
					echo "Não foi possível altualizar o registro: ".$cod.". Erro: ".$e->getMessage();
				}
		break;
		
		case 'desabilitar':
				try {
					$camposValores['habil'] = 0;
					$clihelper->alterar($banco, $camposValores, $filtro);
					echo "desabilitou";
				}
				catch (Exception $e) {
					echo "Não foi possível altualizar o registro: ".$cod.". Erro: ".$e->getMessage();
				}
		break;
		
		case 'atualizar':
				if(!empty($nome))
				{
					try {
						$camposValores['nome']  = $nome;
						$clihelper->alterar($banco, $camposValores, $filtro);
						echo "atualizou";
					}
					catch (Exception $e) {
						echo "Não foi possível altualizar o registro: ".$cod.". Erro: ".$e->getMessage();
					}
				}
				else echo "Você deve preencher todos os campos!";
		break;
		
		case 'excluir':
				try {
					$clihelper->excluir($banco, $filtro);
					echo "excluiu";
				}
				catch (Exception $e) {
					echo "Não foi possível excluir o registro: ".$cod.". Erro: ".$e->getMessage();
				}
		break;
		
		case 'cadastrar':
				if(!empty($nome))
				{
					try{
						$camposValores['nome']  = $nome;
						$novoCodigo = $clihelper->incluir($banco, $camposValores);
						echo "cadastrou-".$novoCodigo;
					}
					catch (Exception $e){
						echo "Não foi possível incluir o registro. Erro: " . $e->getMessage();
					}
				}
				else echo "Você deve preencher todos os campos!";
		break;
		
		case 'listar':
				try {
					$clientes = $clihelper->listarClientes($banco, null);
					echo json_encode($clientes);
				}
				catch (Exception $e) {
					echo "Não foi possível listar os clientes.".$e->getMessage();
				}
		break;
		
		default:
				try {
					$clientes = $clihelper->listarClientes($banco, null);
					echo json_encode($clientes);
				}
				catch (Exception $e) {
					echo "Não foi possível listar os clientes.".$e->getMessage();
				}
		break;
	}
?>