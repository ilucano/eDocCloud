<?php

class ConnectionProperty{
	private static $host = 'localhost';
	private static $user = 'pampita';
	private static $password = 'e8Zv?,vsFV`>NNE';
	private static $database = 'ilucano_edoccloud';

	public static function getHost(){
		return ConnectionProperty::$host;
	}

	public static function getUser(){
		return ConnectionProperty::$user;
	}

	public static function getPassword(){
		return ConnectionProperty::$password;
	}

	public static function getDatabase(){
		return ConnectionProperty::$database;
	}
}
?>