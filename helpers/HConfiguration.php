<?php

class HConfiguration {
	public static function get($name) {
		if (HConfiguration::exists($name))
			return Bundle\DB::Connect()->query("SELECT Value FROM " . DB_PREFIX . "config WHERE Name = '" . $name . "'")->fetch_object()->Value;
			
		return false;
	}
	
	public static function set($name, $value) {
		$c = Bundle\DB::Connect();
		
		if (is_string($value))
            $value = $c->escape_string($value);
            
        if (!HConfiguration::exists($name))
			HConfiguration::create($name, $value);
		else
			$c->query("UPDATE " . DB_PREFIX . "config SET Value = " . $value . " WHERE Name = '" . $name . "'");
	}
	
	public static function create($name, $value) {
		$c = Bundle\DB::Connect();
		
		if (is_string($value))
            $value = "'" . $c->escape_string($value) . "'";
            
		if (!HConfiguration::exists($name))
			$c->query("INSERT INTO " . DB_PREFIX . "config (Name, Value) VALUES ('" . $name . "', " . $value . ")");
	}
	
	public static function exists($name) {
		if (Bundle\DB::Connect()->query("SELECT COUNT(*) as Count FROM " . DB_PREFIX . "config WHERE Name = '" . $name . "'")->fetch_object()->Count == 0)
			return false;
			
		return true;
	}
}
