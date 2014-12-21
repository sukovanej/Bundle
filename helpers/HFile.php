<?php

class HFile extends HFileSystemItem {
	public $Path;
	
	public function __construct($path) {
		$info = pathinfo($file);
		$this->Path = $file;
		
		foreach($info as $key => $value)
			$this->{$key} = ucfirst($value);
	}
}
