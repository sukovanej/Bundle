<?php

/**
 * DatabaseBase
 * @author sukovanej
 */
 
namespace Bundle; 
 
abstract class DatabaseBase {
	/**
	 * N�zev tabulky
	 *
	 */	
    protected $Table;
	
	/**
	 * P�ipojen� k datab�zi
	 *
	 */	
    public $connect;
    
	/**
	 * Vytvo�it instanci
	 *
	 * @param int $ID ID z�znamu
	 * @param string $Table N�zev tabulky v datab�zi
	 * @return mixed This is the return value description
	 *
	 */	
    public function __construct($ID, $Table) {
		$this->connect = DB::Connect();
		
        $this->ID = $ID;
        $this->Table = $this->connect->real_escape_string($Table);
        
        $result = $this->connect->query("SELECT * FROM " . DB_PREFIX . $this->Table . " WHERE ID = " . $ID);

        if (@$result->num_rows < 1)
            throw new \Exception("Not found");

        $row = $result->fetch_assoc();
        
        foreach ($row as $key => $value)
            $this->{$key} = $value;
            
        date_default_timezone_set("Europe/Prague");
    }
    
	/**
	 * Smazat z�znam z DB
	 *
	 */	
    public function Delete() {
        $this->connect->query("DELETE FROM " . DB_PREFIX . $this->Table . " WHERE ID = " . $this->ID);
    }
    
	/**
	 * Upravit z�znam a ulo�it do DB
	 *
	 * @param string $name N�zev sloupce v DB
	 * @param string $value Nov� hodnota
	 *
	 */	
    public function Update($name, $value) {
        if (is_string($value))
            $value = "'" . $this->connect->escape_string($value) . "'";
        
        $this->connect->query("UPDATE " . DB_PREFIX . $this->Table . " SET " . $name . " = " . $value . " WHERE ID = " 
                . $this->ID);
                
        $this->{$name} = $value;
    }
    
	/**
	 * Aktualizovat aktu�ln� instanci t��dy
	 *
	 */	
    public function InstUpdate() {
        $result = $this->connect->query("SELECT * FROM " . DB_PREFIX . $this->Table . " WHERE ID = " . $this->ID);
        $row = $result->fetch_assoc();
        
        foreach ($row as $key => $value)
            $this->{$key} = $value;
    }
}
