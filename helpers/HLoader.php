<?php
	$abstracts = array(
		"HFileSystemItem.php"
	);
	
	foreach($abstracts as $file) {
		require($file);
	}
	
	if ($handle = opendir("helpers")) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != ".." && $entry != "HLoader.php" && $entry != ".htaccess" && $entry != "debug" && !in_array($entry, $abstracts)) {
				require_once($entry);
			}
		}
		closedir($handle);
	}
