<?php 
require 'vendor/autoload.php';
include 'functions.php';
include 'ftp.php';
include 'constants.php';

date_default_timezone_set('Europe/Madrid');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Primera parte del proceso. Conectarse FTP y descargar los ficheros
$connId = connect('tdi', 'Fichero&2020');
process($connId, FTP_DIR_TDI, LOCAL_TDI_DIR);

//Segunda parte tratar los ficheros descargados
$directorio = opendir(ENTRADA_TDI_DIR); //ruta actual

while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
	 if( $archivo != "." && $archivo != "..")
	 {
		read_file($archivo);
	 }
}

function read_file($file){

	$exito = true;
	//Obtener contenido	
	$string = file_get_contents(ENTRADA_TDI_DIR.$file);
		
	//Procesar el archivo
	$response = processDataTDI($string);
	
	echo "respuesta: ".  $response->code."-".$response->raw_body."<br>";
	
	if ($response->code != 200){
			//Escribir un fichero
			write_file($response->code, $response->raw_body, $file, "error");
			$exito = false;
	}
	
	//Si va bien movemos el fichero
	if ($exito){
		rename (ENTRADA_TDI_DIR.$file, PROCESADO_TDI_DIR.$file);
	}
}
	//Escribe en un fichero los errores 
	function write_file($code, $detalle, $file, $filename ){
		$fecha = date("Y-m-d_H:i:s");
		$fechaFichero = date("Ymd");
		$csv_end = "\n";  
		$csv_sep = "|";  
		$csv_file = ERRORES_TDI_DIR.$fechaFichero."-".$filename.".csv";  
		$row="";  

		$row.= $fecha.$csv_sep.$code.$csv_sep.$detalle.$file.$csv_sep.$csv_end;  

		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($csv_file, $row, FILE_APPEND | LOCK_EX);
	}
?>