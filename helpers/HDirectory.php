<?php

class HDirectory extends HFileSystemItem {
	const REWRITE_ON = true;
	const REWRITE_OFF = false;
	
	const FILE_NAME = "default_file_name";
	
	public $Path;
	
	// path format: /dir/sub_dir/.../you_dir
	public function __construct($path) {
		$this->Path = $path;
	}
	
	public function get() {
		$files = scandir($this->Path);
		$a_result = array();
		
		for($i = 2; $i < count($files); $i++) {
			if (is_dir($files[$i]))
				$a_result[] = new HDirectory($this->Path . "/" . $files[$i]);
			else
				$a_result[] = new HFile($this->Path . "/" . $files[$i]);
		}
			
		return $a_result;
	}
	
	public function uploadFile($file, $name = self::FILE_NAME, $max_size = false, $rewrite = self::REWRITE_OFF) {
		$target_file = $this->Path . "/" . basename($file["name"]);
		
		if ($name == self::FILE_NAME)
			$file_name = $file["tmp_name"];
		else
			$file_name = $name;
		
		if (!$rewrite && file_exists($target_file))
			throw new Exception("Soubor " . $target_file . " už existuje.");
		
		if($max_size != false && $file['size'] > $max_size) {
			throw new Exception("Překročena maximální velikost souboru " . $max_size . "B.");
		} else {
			if (move_uploaded_file($file_name, $target_file))
				throw new Exception("Neznámá chyba při uploadu.");
		}
	}
	
	public function newDirectory($dir, $mode = 0777, $recursive = false) {
		if(mkdir($dir, $moe, $recursive))
			return new HDirectory($this->Path);
			
		throw new Exception("Složka <em>" . $dir . "</em> nelze vytvořit.");
	}
}
