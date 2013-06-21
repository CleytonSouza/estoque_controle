<?php
	//periodo em segundos
	$inactive = 3000;
	echo "inactive: ".$inactive."<br>";
	 
	if(isset($_SESSION['timeout']))
	{
		echo "_SESSION['timeout']: ".$_SESSION['timeout']."<br>";
		$session_life = time() - $_SESSION['start'];
		
		echo "session_life: ".$session_life."<br>";
		
		if($session_life > $inactive)
		{
			header("Location: ../logout.php");
		}
	}
	
	$_SESSION['timeout'] = time();
?>