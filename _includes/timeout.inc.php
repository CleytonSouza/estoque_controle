<?php
	isset($_SESSION["ultimo_acesso"]) ? $data_salva = $_SESSION["ultimo_acesso"] : $data_salva = 0;
	
    $data_agora   = date("Y-n-j H:i:s");
    $tempo_ocioso = (strtotime($data_agora) - strtotime($data_salva));

	if($tempo_ocioso >= 1800)
	{
		header("Location:../cgeral/logout.php");
	}
	else
	{
		$_SESSION["ultimo_acesso"] = $data_agora;
	}
	
	$tests['data_salva']   = $data_salva;
	$tests['data_agora']   = $data_agora;
	$tests['tempo_ocioso'] = $tempo_ocioso;
?>