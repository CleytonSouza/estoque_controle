<?php
	require_once ('_classes/Constantes.class.php');
	require_once ('_classes/_MODEL/Usuario.class.php');
	
	session_start();
	
	if(isset($_SESSION[Constantes::OBJETO_USUARIO]))
	{
		$usuario = $_SESSION[Constantes::OBJETO_USUARIO];
		
		$user[0]['nome']  = $usuario->getNome();
		$user[0]['nivel'] = $usuario->getNivel();
	}
	else
	{
		$user[0]['nome']  = null;
		$user[0]['nivel'] = null;
	}
	
	echo json_encode($user);
?>