<?php

class HFileSystemItem {
	public function isFile() {
		if (get_called_class() == "HDirectory")
			return false;
		
		return true;
	}
	
	public function isDirectory() {
		if(!$this->isFile())
			return true;
			
		return false;
	}
}
