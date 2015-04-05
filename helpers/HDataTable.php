<?php

class HDataTable {
	public $Columns = array();
	public $ClassicColumns = array();
	
	public function __construct($dbTable, $columns = null) {
		$this->connect = Bundle\DB::Connect();
		$this->simple_db_result = $this->connect->query("SELECT * FROM " . $dbTable . "");
		
		foreach($this->simple_db_result->fetch_fields() as $field) {
			$this->ClassicColumns[$field->name] = $field->name;
		}
		
		if ($columns == null) {
			foreach ($this->ClassicColumns as $value)
				$this->Columns["{" . $value . "}"] = $value;
		} else {
			$this->Columns = $columns;
		}
	}
	
	public function render() {
		$result = "\n<table class=\"table table-hover\">\n";
		$result .= "\t<thead><tr>\n";
		
		foreach ($this->Columns as $key => $value)
			$result .= "\t\t<th>" . $value . "</th>\n";
			
		$result .= "\t<tr></thead><tbody>\n";	
		
		while ($item = $this->simple_db_result->fetch_object()) {
			$result .= "\t<tr>\n";
			
			foreach ($this->Columns as $key => $value) {
				$n_key = $key;
				
				foreach ($this->ClassicColumns as $x) {
					$n_key = str_replace("{" . $x . "}", $item->{$x}, $n_key);
				}
					
				$result .= "\t\t<td>" . $n_key . "</td>\n";
			}
			
			$result .= "\t<tr>\n";
		}
		
		$result .= "</tbody></table>\n";
		
		echo $result;
	}
}
