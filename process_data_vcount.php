<?php
	require 'vendor/autoload.php';
	include 'functions.php';
	
	date_default_timezone_set('Europe/Madrid');
	//Parametros de url
	$queryString = $_SERVER['QUERY_STRING'];
	
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	$UUID = $_REQUEST['UUID'];
	$SerialID = $_REQUEST['SerialID'];
	$MeasurementStartTime = $_REQUEST['MeasurementStartTime'];
	$MeasurementEndTime = $_REQUEST['MeasurementEndTime'];
	$OutgoingHumanCount = $_REQUEST['OutgoingHumanCount'];
	$IncomingHumanCount = $_REQUEST['IncomingHumanCount'];
	$CountingPeriod = $_REQUEST['CountingPeriod'];
	
	/*echo "username: ".$username . "</br>";
	echo "password: ".$password . "</br>";
	echo "UUID: ".$UUID . "</br>";
	echo "SerialID: ".$SerialID . "</br>";
	echo "MeasurementStartTime: ".$MeasurementStartTime . "</br>";
	echo "MeasurementEndTime: ".$MeasurementEndTime . "</br>";
	echo "OutgoingHumanCount: ".$OutgoingHumanCount . "</br>";
	echo "IncomingHumanCount: ".$IncomingHumanCount . "</br>";
	echo "CountingPeriod: ".$CountingPeriod . "</br>";*/


	$response = processDataVcount($username,$password,$UUID,$SerialID, $MeasurementStartTime,
	$MeasurementEndTime,$OutgoingHumanCount,$IncomingHumanCount, $CountingPeriod);
	
	echo $response->code;		
	
	if ($response->code != 200){
		//Mandar un mail
		write_file($queryString, $response->code, $response->raw_body, "errores");
	}


	//Escribe en un fichero los errores 
	function write_file($queryString, $code, $detalle, $filename ){
		$fecha = date("Y-m-d_H:i:s");
		$fechaFichero = date("Ymd");
		$csv_end = "\n";  
		$csv_sep = "|";  
		$csv_file = $fechaFichero."-".$filename.".csv";  
		$row="";  

		$row.= $queryString.$csv_sep.$code.$csv_sep.$detalle.$csv_end;  

		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($csv_file, $row, FILE_APPEND | LOCK_EX);
	}
	
?>  
