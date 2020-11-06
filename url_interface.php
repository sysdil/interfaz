<?php
	require 'vendor/autoload.php';
	include 'functions.php';
	
	date_default_timezone_set('Europe/Madrid');

	$class = $_REQUEST['class'];
	$type = $_REQUEST['type'];
	$productRef = $_REQUEST['prod_ref'];
	$strFecha = $_REQUEST['date'];
	$url = $_SERVER['REQUEST_URI'];


	//Solo controlamos entradas y salidas
	if ($class == 'add_inout' and $type=='IN'){
		$response = addEntry($productRef, $strFecha);
	}else if ($class == 'add_inout' and $type=='OUT'){
		$response = addExit($productRef, $strFecha);
	} else{
		//Sino es un clase de las esperadas devolvemos OK, no las soportamos
		echo "OK";
		return;
	}
		
	if ($response->code != 200){
		//Mandar un mail
		write_file($productRef,$strFecha, $type, $response->code, $response->raw_body, "entradas");
	}

	//Siempre devuelvo OK, porque los errores de llamadas entre servicios los gesionamos nosotros		
	echo "OK";

	//Escribe en un fichero los errores 
	function write_file($productRef,$strFecha, $type, $code, $detalle, $filename ){
		$fecha = date("Y-m-d_H:i:s");
		$fechaFichero = date("Ymd");
		$csv_end = "\n";  
		$csv_sep = "|";  
		$csv_file = $fechaFichero."-".$filename.".csv";  
		$row="";  

		$row.= $fecha.$csv_sep.$productRef.$csv_sep.$strFecha.$csv_sep.$type.$csv_sep.$code.$csv_sep.$detalle.$csv_end;  

		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($csv_file, $row, FILE_APPEND | LOCK_EX);
	}
	
?>  
