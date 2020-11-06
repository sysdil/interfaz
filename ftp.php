<?php
/*
include 'constants.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

const FTP_HOST='99.80.98.243';

	function connect($username, $password){
			
		// open an FTP connection
		$connId = ftp_connect(FTP_HOST) or die("No se puede conectar a $ftpHost");

		// login to FTP server
		$ftpLogin = ftp_login($connId, $username, $password);
		
		ftp_pasv($connId, true) or die("Ha fallado la conexin pasiva");
				
		return $connId;
	}
	
	function process($connId, $ftpDir, $ftpLocalDir) {
			
		// get contents of the current directory
		$contents = ftp_nlist($connId, $ftpDir);		
		
		if(is_array($contents)){
			foreach ($contents as &$valor) {		
				//Primero transferimos los ficheros a la carpeta local
				echo ("Transfiriendo: $valor <br/>" );
				transferFile($connId,str_replace ($ftpDir,$ftpLocalDir,$valor),$valor);		
				//Borramos el fichero del ftp
				deleteFile($connId,$valor);
			}	
		}
		
		ftp_close($connId);
	}
	

	function transferFile($connId,$localFilePath,$remoteFilePath){
		
		if(!ftp_get($connId, $localFilePath, $remoteFilePath, FTP_BINARY)){
			echo "Ha habido un problema descargando el fichero $localFilePath";
		}else{
		 echo ("Se transfiere el fichero $remoteFilePath a $localFilePath </br>");
		}
	}
	
	function deleteFile($connId,$file){
		
		if(!ftp_delete($connId, $file)){
			echo "Ha habido un problema eliminando el fichero $file";
		}else{
			echo "Se eliminando el fichero $file del ftp";
		}			
	}

?>