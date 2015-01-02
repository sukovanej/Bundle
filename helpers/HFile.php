<?php

class HFile extends HFileSystemItem {
	public $Path;
	
	public function __construct($path) {
		$info = pathinfo($path);
		$this->Path = $path;
		
		foreach($info as $key => $value)
			$this->{$key} = ucfirst($value);
	}
}
