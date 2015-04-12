<?php
namespace Bundle;
class IniConfig {
	public $Array;
	
	/**
	 * Parsovat soubor
	 *
	 * @param string $url Adresa k souboru
	 * @param bool $trim Odstranit bílé znaky na konci a začátku řetězců?
	 *
	 */		
	public function __construct($url, $trim = true) {
		$this->Array = array();
		
		if (!file_exists($url) && file_exists("../../" . $url)) { // modifikace kvůli ajaxovému volání
			$url = "../../" . $url;
		}

		if (!file_exists($url)) {
			throw new \Exception("Soubor " . $url . " nebyl nalezen!");
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
