<?php

function SplitSQL($file, $mysqli, $delimiter = ';') {
	set_time_limit(0);
	
	$return = "<strong>SQL instalace</strong>: \n";
	
	if (is_file($file) === true)
	{
		$file = fopen($file, 'r');

		if (is_resource($file) === true)
		{
			$query = array();

			while (feof($file) === false)
			{
				$query[] = fgets($file);

				if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1)
				{
					$query = trim(implode('', $query));

					if ($mysqli->query($query) === false)
						$return .= '<h3>Provedeno: ' . $query . '</h3>' . "\n";
					else
						$return .= '<h3>Chyba: ' . $query . '</h3>' . "\n";
					
					while (ob_get_level() > 0)
						ob_end_flush();

					flush();
				}

				if (is_string($query) === true)
					$query = array();
			}
			
			fclose($file);

			return $return;
		}
	}
	
	return false;
}
