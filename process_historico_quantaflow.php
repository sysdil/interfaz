<?php 
require 'vendor/autoload.php';
include 'functions.php';
include 'ftp.php';
include 'constants.php';

date_default_timezone_set('Europe/Madrid');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Cargar la excel de datos

$directorio = opendir(ENTRADA_QUANTAFLOW_DIR); //ruta actual

while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
	 if( $archivo != "." && $archivo != "..")
	 {
		read_file($archivo);
	 }
}

function read_file($file){
	//Abrimos nuestro archivo
	$archivo = fopen(ENTRADA_QUANTAFLOW_DIR.$file, "r");
	$exito = true;
	$idCliente =13;
	$idCentro =0;
	$numColumnas=4;
	$acceso = array();
	$visitas = array();
	$visitasZona = array();
	$data = array();
	$zonas= array();
	$esEntrada = array();
	$row=0;

	echo "Proceso de vocado de histórico de quantaflow: INIT /n";
	
	echo "Tratando fichero:$file /n" ;	

	//Lo recorremos pero solo puede tener una línea	
	while (($datos = fgetcsv($archivo, 0,";", '"')) == true) 
	{		
		//La primera vez cogemos del centro, que sera la columna 1
		if ($row==0){
			$idCentro = $datos[1];		
		} else if ($row == 1){
			for ($i = 1; $i <= $numColumnas; $i++) {				
				array_push($zonas,$datos[$i]);
			}		
			//array_push($zonas,$datos[1],$datos[2],$datos[3],$datos[4]);
		}else if ($row == 2){
			//Guardar datos de si es entrada o no para las sumas
			for ($z = 1; $z <= $numColumnas; $z++) {					
				array_push($esEntrada,$datos[$z]);		
			}	
			
		} else if ($row > 2){			
			$fecha = $datos[0];
			//Creamos una lista con los valores y las horas
			for ($j =1; $j <= $numColumnas; $j++) {	
				array_push($visitasZona,array('idAcceso' => $zonas[$j-1], 'numVisitas' => $datos[$j], 'esEntrada' => $esEntrada[$j-1]));
			}
							
			//Creo la lista de zona y visitas
			$data = array('idCliente' => $idCliente, 'idCentro' => $idCentro,'fecha' => $fecha,'visitasAcceso' => $visitasZona);
					
			sendData($data);
		}
		$visitasZona = array();
		$row++;
	}
	
	//Cerramos el archivo
	fclose($archivo);
	//Si va bien movemos el fichero
	if ($exito){
		rename (ENTRADA_QUANTAFLOW_DIR.$file, PROCESADO_QUANTAFLOW_DIR.$file);
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
		 $response = processDataQuantaflow($data);
		 echo "respuesta: ".  $response->code."-".$response->raw_body."/n";
		 
		 if ($response->code != 200){
			//Escribir un fichero
			write_file($response->code, $response->raw_body, $file, "error");
			$exito = false;
		}

	}
?>