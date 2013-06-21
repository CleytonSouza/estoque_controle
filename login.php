<?php
	$gmtDate = gmdate("D, d M Y H:i:s");
	header("Expires: {$gmtDate} GMT"); 
	header("Last-Modified: {$gmtDate} GMT"); 
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1");
	
	require_once ('_classes/FiltroSQL.class.php');
	require_once ('_classes/Constantes.class.php');
	
	require_once ('_classes/_MODEL/Usuario.class.php');
	require_once ('_classes/_HELPER/UsuarioHelper.class.php');
	
	require_once ('_includes/confconexao.inc.php');
	require_once ('_includes/retornaconexao.inc.php');
	
	if(isset($_POST['l']) && isset($_POST['s']))
	{		
		$banco = $_SESSION[BANCO_SESSAO];
		
		$login = $_POST['l'];
		$senha = $_POST['s'];
		
		$mensagem  = "";
		$resultado = array();
		$usuhelper = new UsuarioHelper();
		
		$filtro = new FiltroSQL(FiltroSQL::CONECTOR_E, FiltroSQL::OPERADOR_IGUAL, array("login" => $login, "senha" => $senha));
		
		try {
			$resultado = $usuhelper->consultar($banco, null, $filtro);
		}
		catch (Exception $e) {
			$mensagem = "Erro ao consultar usuário. Erro: " . $e->getMessage();
		}
		
		if(count($resultado) > 0)
		{
			foreach($resultado as $usuario)
			{
				$_SESSION['autorizado']               = "SIM";
				$_SESSION['ultimo_acesso']            = date("Y-n-j H:i:s"); 
				$_SESSION[Constantes::OBJETO_USUARIO] = $usuario;
				
				$nome  = $usuario->getLogin();
				$nivel = "0";
				//$nivel = $usuario->getNivel();
				//$user['nome']  = $nome;
				//$user['nivel'] = $nivel;
				
				//echo json_encode($user);
				echo "ok-".$nome."-".$nivel;
			}
		}
		else
			echo 'invalido';
	}
	else
		return false;
?>