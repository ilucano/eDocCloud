<?php

require_once 'db.prop.php';

class NConnectionFactory{
	
	static public function getConnection(){
		
		
		try {
			
			$conn = new PDO(
				'mysql:host='.ConnectionProperty::getHost().';dbname='.ConnectionProperty::getDatabase(),
				ConnectionProperty::getUser(),
				ConnectionProperty::getPassword()
			);
			
		} catch (PDOException $e) {
			echo 'Falló la conexión: ' . $e->getMessage();
		}
		
		return $conn;
	}

	static public function close($connection){
		$connection = null;
	}
}

?>