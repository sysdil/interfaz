<?php 
require 'vendor/autoload.php';
require 'enviroment.php';

//Envia una entrada a WS de Ontrace
function addEntry($productRef, $strFecha){
		
	///die(var_dump($productRef));
	$headers = array('Content-Type' => 'application/json');
	$data = array('productReference' => $productRef, 'datetime' => $strFecha);	
	$body = Unirest\Request\Body::json($data);
	
	$response = Unirest\Request::post(URL_APP.'/interfaz/ontrace/addEntry', $headers, $body);
	
	return $response;
}

//Envia una entrada a WS de Ontrace
function addExit($productRef, $strFecha){
	
	$headers = array('Content-Type' => 'application/json');
	$data = array('productReference' => $productRef, 'datetime' => $strFecha);

	$body = Unirest\Request\Body::json($data);

	$response = Unirest\Request::post(URL_APP.'/interfaz/ontrace/addExit', $headers, $body);
	
	return $response;
}

//Envia una entrada a WS de Megacount
function processDataMegacount($filename,$data){
		
	$headers = array('Content-Type' => 'application/json');

	$response = Unirest\Request::post(URL_APP.'/interfaz/megacount/addData/'.$filename, $headers, $data);
	
	return $response;
}

//Envia una entrada a WS de historicos de Megacount
function processDataHistoricMegacount($id, $fecha, $nombreCentro, $numVisitas, $numSalidas){
	
	$headers = array('Content-Type' => 'application/json');
	$data = array('id' => $id, 'fecha' => $fecha, 'nombreCentro' => $nombreCentro, 'numVisitas' => $numVisitas, 'numSalidas' => $numSalidas);	
	$body = Unirest\Request\Body::json($data);
	
	$response = Unirest\Request::post(URL_APP.'/interfaz/megacount/addHistoricData/', $headers, $body);
	
	return $response;
}

//Envia una entrada a WS de TDI
function processDataTDI($data){
		
	$headers = array('Content-Type' => 'application/json');

	$response = Unirest\Request::post(URL_APP.'/interfaz/tdi/addData', $headers, $data);
	
	return $response;
}

//Envia datos a WS de VCount
function processDataVcount($username,$password,$UUID,$SerialID, $MeasurementStartTime,
	$MeasurementEndTime,$OutgoingHumanCount,$IncomingHumanCount, $CountingPeriod){
		
	$headers = array('Content-Type' => 'application/json');
	
	$data = array('username' => $username, 'password' => $password,'uuid' => $UUID,'serialID' => $SerialID,
	'measurementStartTime' => $MeasurementStartTime,'measurementEndTime' => $MeasurementEndTime,
	'outgoingHumanCount' => $OutgoingHumanCount,'incomingHumanCount' => $IncomingHumanCount,
	'countingPeriod' => $CountingPeriod);
	
	$body = Unirest\Request\Body::json($data);

	
	$response = Unirest\Request::post( URL_APP.'/interfaz/vcount/addData', $headers, $body);
	
	return $response;
}
//Envia datos a WS de Quantaflow
function processDataQuantaflow($data){
	
	$headers = array('Content-Type' => 'application/json');
	
	$body = Unirest\Request\Body::json($data);

	$response = Unirest\Request::post( URL_APP.'/interfaz/quantaflow/addHistoricData', $headers, $body);
	
	return $response;

}
//Envia datos a WS de Quantaflow
function processDataHoraQuantaflow($data){
	
	$headers = array('Content-Type' => 'application/json');
		
	$body = Unirest\Request\Body::json($data);
	
	$response = Unirest\Request::post( URL_APP.'/interfaz/quantaflow/addHistoricDataByHour', $headers, $body);
	
	return $response;

}

?>