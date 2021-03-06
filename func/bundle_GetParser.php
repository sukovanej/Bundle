<?php
namespace Bundle; 

class GetParser {
	/**
	 * Parsovat parametry v URL
	 *
	 *
	 */	
	public function __construct() {
		$this->parse();
	}
	
	/**
	 * Parsovat parametry v adrese URL
	 *
	 * @return object This
	 *
	 */	
	public function parse() {
		$url = $_SERVER["REQUEST_URI"];
		$this->Data = preg_split("[\\?]", $url);
		if (count($this->Data) > 1) {
			$array = preg_split("[&]", $this->Data[1]);
			
			foreach($array as $data) {
				$d = preg_split("[=]", $data);
				
				if (!isset($d[1]))
					$d[1] = false;
				
				if (!empty($d[0]))
					$this->{$d[0]} = $d[1];
			}
		}
		
		return $this;
	}
}
