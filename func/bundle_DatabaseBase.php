<?php

/**
 * DatabaseBase
 * @author sukovanej
 */
 
namespace Bundle; 
 
abstract class DatabaseBase {
	/**
	 * Název tabulky
	 *
	 */	
    protected $Table;
	
	/**
	 * Pøipojení k databázi
	 *
	 */	
    public $connect;
    
	/**
	 * Vytvoøit instanci
	 *
	 * @param int $ID ID záznamu
	 * @param string $Table Název tabulky v databázi
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
	 * Smazat záznam z DB
	 *
	 */	
    public function Delete() {
        $this->connect->query("DELETE FROM " . DB_PREFIX . $this->Table . " WHERE ID = " . $this->ID);
    }
    
	/**
	 * Upravit záznam a uložit do DB
	 *
	 * @param string $name Název sloupce v DB
	 * @param string $value Nová hodnota
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
	 * Aktualizovat aktuální instanci tøídy
	 *
	 */	
    public function InstUpdate() {
        $result = $this->connect->query("SELECT * FROM " . DB_PREFIX . $this->Table . " WHERE ID = " . $this->ID);
        $row = $result->fetch_assoc();
        
        foreach ($row as $key => $value)
            $this->{$key} = $value;
    }
}
