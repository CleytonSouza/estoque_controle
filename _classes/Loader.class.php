<?php 
	interface Loader { }
	function __autoload($classe){
		
		$path[0] = "../_classes/";
		$path[1] = "../_classes/_DAO/persistivel/";
		$path[2] = "../_classes/_DAO/";
		$path[3] = "../_classes/_HELPER/";
		$path[4] = "../_classes/_MODEL/";
		$path[5] = "../_classes/_RN/";
		$path[6] = "../_classes/fpdf/";
		
		for($i=0; $i<sizeof($path); $i++)
		{
			if(file_exists($path[$i].$classe.".class.php"))
			{
				require_once $path[$i].$classe.".class.php";
			}
		}
	}
?>