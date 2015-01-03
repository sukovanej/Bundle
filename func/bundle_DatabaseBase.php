<?php

/**
 * DatabaseBase
 * @author sukovanej
 */
 
namespace Bundle; 
 
abstract class DatabaseBase {
    protected $Table;
    public $connect;
    
    public function __construct($ID, $Table) {
		$this->connect = DB::Connect();
		
        $this->ID = $ID;
        $this->Table = $this->connect->real_escape_string($Table);
        
        $result = $this->connect->query("SELECT * FROM " . DB_PREFIX . $this->Table . " WHERE ID = " . $ID);
        $row = $result->fetch_assoc();
        
        foreach ($row as $key => $value)
            $this->{$key} = $value;
            
        date_default_timezone_set("Europe/Prague");
    }
    
    public function Delete() {
        $this->connect->query("DELETE FROM " . DB_PREFIX . $this->Table . " WHERE ID = " . $this->ID);
    }
    
    public function Update($name, $value) {
        if (is_string($value))
            $value = "'" . $this->connect->escape_string($value) . "'";
        
        $this->connect->query("UPDATE " . DB_PREFIX . $this->Table . " SET " . $name . " = " . $value . " WHERE ID = " 
                . $this->ID);
    }
    
    public function InstUpdate() {
        $result = $this->connect->query("SELECT * FROM " . DB_PREFIX . $this->Table . " WHERE ID = " . $this->ID);
        $row = $result->fetch_assoc();
        
        foreach ($row as $key => $value)
            $this->{$key} = $value;
    }
}
