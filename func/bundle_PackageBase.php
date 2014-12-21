<?php
namespace Bundle;

abstract class PackageBase {
	private $includes;
	
	public function __construct() {
		
	}
	
	public function IncludeAllFiles() {
		foreach($this->includes as $include)
			require($include);
	}
}
