<?php

/**
 * iniConfig
 *
 * @author sukovanej
 */
 
namespace Bundle;  

class IniConfig {
	public $Array;
	
    public function __construct($url, $trim = true) {  
		$this->Array = array();
		      
        if (!file_exists($url)) {
			$this->Error = true;
		} else { 
			$handle = @fopen($url, "r");
			
			if ($handle) {
				if ($trim) {
					while (($buffer = fgets($handle, 4096)) !== false) {
						$foreach_array = explode("=", $buffer);
						$this->{trim($foreach_array[0])} = trim($foreach_array[1]);
						$this->Array[trim($foreach_array[0])] = trim($foreach_array[1]);
					}
				} else {
					while (($buffer = fgets($handle, 4096)) !== false) {
						$foreach_array = explode("=", $buffer);
						$this->{trim($foreach_array[0])} = $foreach_array[1];
						$this->data[trim($foreach_array[0])] = $foreach_array[1];
						$this->Array[trim($foreach_array[0])] = $foreach_array[1];
					}
				}
			}
			
			$this->Error = false;
			
			fclose($handle);
		}       
    }
}
