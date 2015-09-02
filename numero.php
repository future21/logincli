<?php

	include_once ("clase.php");
	//if (isset($_GET['tag']) && $_GET['tag'] != '') {
	// Get tag
//	error_reporting(E_ALL);
	$tag = 	$tag=$fastagi->request[agi_arg_1];
	$movil = $fastagi->request[agi_arg_2];

	if ($tag == 'largo') {	
	
		$result = largo($movil);
		if ($result != false) { 
			//echo "exec SET VARIABLE corto". $result["corto"];
			$fastagi->set_variable("numerocorto", $result["n_corto"]);				
			
		} else { echo "ERROR BUSQUEDA MYSQL"; }
		
	}

	if ($tag == 'corto') {	
	
		$result = corto($movil);
		if ($result != false) { 
			//echo "exec SET VARIABLE largo". $result["largo"];
			$fastagi->set_variable("numerocorto", $result["n_largo"]);				
			
		} else { echo "ERROR BUSQUEDA MYSQL"; }
		
	}

	
?>
