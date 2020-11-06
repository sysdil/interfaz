<?php
// required headers
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

    //echo "datos" . $data;
	write_file ($data);
 
    // tell the user
    //echo json_encode(array("message" => $data));
	http_response_code(200);
*/
	write_file ("BODY: " . file_get_contents('php://input'));
	
	foreach($_POST as $campo => $valor){
      write_file ("POST - ". $campo ." = ". $valor);
    }
    
    foreach($_GET as $sesionGet => $valorGet){
      write_file ("GET - ". $sesionGet ." = ". $valorGet);
    }

    foreach($_SERVER as $server => $valorS){
      write_file("SERVER -". $server ." = ". $valorS);
    }
    
//     foreach($_SESSION as $sesion => $valorSe){
//     echo ("SESSION -". $sesion ." = ". $valorSe);
//     }
    
    foreach($_FILES as $sesionF => $valorF){
    write_file ("FILES -". $sesionF ." = ". $valorF);
    }
    
    foreach($_COOKIE as $sesionC => $valorC){
    write_file ("COOKIE -". $sesionC ." = ". $valorC);
    }
    
    foreach($_REQUEST as $sesionR => $valorR){
    write_file ("REQUEST -". $sesionR ." = ". $valorR);
    }
    
    foreach($_ENV as $sesionE => $valorE){
    write_file ("ENV -". $sesionE ." = ". $valorE);
    }
    
//     foreach($GLOBALS as $sesionG => $valorG){
//     echo ("GLOBALS -". $sesionG ." = ". $valorG);
//     }
    
//     foreach($HTTP_SERVER_VARS as $sesionV => $valorV){
//     echo ("HTTP_SERVER_VARS -". $sesionV ." = ". $valorV);
//     }
    
    $headers = apache_request_headers();
    foreach($headers as $headrerC => $valorHe){
      write_file ("headers -". $headrerC ." = ". $valorHe);
    }

	http_response_code(200);

	//Escribe en un fichero los errores 
	function write_file($data){
		$fecha = date("Y-m-d_H-i-s");
		$fechaFichero = date("Y-m-d_H-i-s");
		$csv_end = "\n";  
		$csv_sep = "|";  
		$csv_file = $fechaFichero."-megacount.txt";  
		$row="";  

		//$row.= json_encode(array("message" => $data)).$csv_end;  
		$row.= $data.$csv_end;  
		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		//file_put_contents($csv_file, $row, FILE_APPEND | LOCK_EX);
	}
?>