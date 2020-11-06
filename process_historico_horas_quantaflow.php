<?php 
require 'vendor/autoload.php';
include 'functions.php';
include 'ftp.php';
include 'constants.php';

date_default_timezone_set('Europe/Madrid');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$directorio = opendir(ENTRADA_QUANTAFLOW_DIR); //ruta actual

while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
	 if( $archivo != "." && $archivo != "..")
	 {
		read_file($archivo);
	 }
}

function read_file($file){
	//Constantes para el caso en particular
	$numColumnas=23;
	$idCliente =13;
	$idCentro =2113;
	
	//Abrimos nuestro archivo
	$archivo = fopen(ENTRADA_QUANTAFLOW_DIR.$file, "r");
	$exito = true;

	$fecha;
	$horas = array();
	$visitasHora = array();
	$data;
	
	$row=0;
	
	echo "Proceso de vocado de histórico por horas de quantaflow: INIT /n";

	//Lo recorremos pero solo puede tener una línea	
	while (($datos = fgetcsv($archivo, 0,";", '"')) == true) 
	{	echo "Tratando fichero:$file </n>" ;		
		//La primera vez cogemos las horas
		if ($row==0){
			for ($i = 1; $i <= $numColumnas; $i++) {
					array_push($horas,$datos[$i]);
			}		
		} else{
			$fecha = $datos[0];
			//Creamos una lista con los valores y las horas
			for ($j =1; $j <= $numColumnas-1; $j++) {
				array_push($visitasHora,array('hora' => $horas[$j], 'numVisitas' => $datos[$j]));
			}	
		//Creo los datos	
		$data = array('fecha' => $fecha,'idCliente' => $idCliente, 'idCentro' => $idCentro, 'visitasHora' => $visitasHora);
		sendData($data);
		}		
		$visitasHora = array();		
		$row++;
	}
	

	//Cerramos el archivo
	fclose($archivo);
	
	//Si va bien movemos el fichero
	if ($exito){
		echo "Proceso de vocado de histórico por horas de quantaflow: FIN /n";
		rename (ENTRADA_QUANTAFLOW_DIR.$file, PROCESADO_QUANTAFLOW_DIR.$file);
	}else{
		echo "Proceso de vocado de histórico por horas de quantaflow CON ERRORES: FIN /n";
	}
	
}
	//Escribe en un fichero los errores 
	function write_file($code, $detalle, $file, $filename ){
		$fecha = date("Y-m-d_H:i:s");
		$fechaFichero = date("Ymd");
		$csv_end = "\n";  
		$csv_sep = "|";  
		$csv_file = ERRORES_QUANTAFLOW_DIR.$fechaFichero."-".$filename.".csv";  
		$row="";  

		$row.= $fecha.$csv_sep.$code.$csv_sep.$detalle.$file.$csv_sep.$csv_end;  

		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($csv_file, $row, FILE_APPEND | LOCK_EX);
	}
	
	function sendData($data){
		 $response = processDataHoraQuantaflow($data);
		 echo "respuesta: ".  $response->code."-".$response->raw_body."</n>";
		 
		 if ($response->code != 200){
			//Escribir un fichero
			write_file($response->code, $response->raw_body, $file, "error");
			$exito = false;
		}

	}
?>