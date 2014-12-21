<?php
namespace Bundle; 

class GetParser {
	public function __construct() {
		$this->parse();
	}
	
	public function parse() {
		$url = $_SERVER["REQUEST_URI"];
		$this->Data = preg_split("[\\?]", $url);
		if (count($this->Data) > 1) {
			$array = preg_split("[&]", $this->Data[1]);
			
			foreach($array as $data) {
				$d = preg_split("[=]", $data);
				
				if (!isset($d[1]))
					$d[1] = false;
				
				$this->{$d[0]} = $d[1];
			}
		}
		
		return $this;
	}
}
