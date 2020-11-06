<?php 
require 'vendor/autoload.php';
include 'functions.php';
include 'constants.php';

$directorio = opendir(ERRORES_ONTRACE_DIR); //ruta actual
while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
	 if( $archivo != "." && $archivo != "..")
	 {
		read_file($archivo);
	 }
}

function read_file($file){

	$linea = 0;
	//Abrimos nuestro archivo
	$archivo = fopen(ERRORES_ONTRACE_DIR.$file, "r");

	//Lo recorremos
	while (($datos = fgetcsv($archivo, 0,"|", '"')) == true) 
	{
	 
		 $num = count($datos);
		 $linea++;
		 $productRef = $datos[1];
		 $strFecha = $datos[2];
		 $type = $datos[3];
		 //echo $productRef."<br>";
		 
		if ($type=='IN'){
			$response = addEntry($productRef, $strFecha);
		}else if ($type=='OUT'){
			$response = addExit($productRef, $strFecha);
		
		}
		
		echo "linea: ". $linea ." respuesta: ".  $response->code ."\n";		
	
	}
	
	//Cerramos el archivo
	fclose($archivo);
}

?>