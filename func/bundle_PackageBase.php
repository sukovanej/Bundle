<?php
namespace Bundle;

abstract class PackageBase {
	public $includes;
	
	public function __construct() {
		if (!isset($this->includes))
			$this->includes = array();
	}
	
	public function IncludeAllFiles() {
		if (is_array($this->includes))
			foreach($this->includes as $include)
				require("packages/" . get_called_class() . "/" . $include);
	}
}
