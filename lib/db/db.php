<?php

require 'db.prop.php';

class ConnectionFactory{
	
	static public function getConnection(){
		//echo ConnectionProperty::getUser();
		$conn = mysql_connect(ConnectionProperty::getHost(), ConnectionProperty::getUser(), ConnectionProperty::getPassword());
		mysql_select_db(ConnectionProperty::getDatabase());
		if(!$conn){
			//throw new Exception('could not connect to database');
			echo 'Could not connect to database';
		}
		return $conn;
	}

	static public function close($connection){
		mysql_close($connection);
	}
}

?>