<?php

/**
 * DB
 *
 * @author sukovanej
 */
 
namespace Bundle; 
 
class DB {
	private static $instance;
	
	private function __construct() {}
		
	/**
	 * Vrátit instanci singleton tøídy DB = pøipojení k databázi
	 *
	 * @return object Pøipojení k databázi
	 *
	 */	
    public static function Connect() {
		if (!isset(self::$instance)) {		
			$config = new IniConfig("config.ini");
        
			$mysqli = new \mysqli(
				$config->host, // server
				$config->user, // user name
				$config->password, // password
				$config->database // database
			);
			
			$mysqli->set_charset("utf8");
			
            self::$instance = $mysqli;
        }
        
        return self::$instance;
    }
	
	/**
	 * Vrátit velikost databáze
	 *
	 * @return int Velikost databáze
	 *
	 */	
    public static function Size() {
		$db = self::Connect();
		
		$q = $db->query("SHOW TABLE STATUS");  
		$size = 0; 
		 
		while($row = $q->fetch_object())
			$size += $row->Data_length + $row->Index_length;
		
		return number_format($size/(1000000), 2);
	}
}
