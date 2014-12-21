<?php
	class bundle_Download_category_DB extends Bundle\DatabaseBase {
		public function __construct($ID) {
			if ($ID == -1) 
				$this->Title = "Nezařazeno";
			else
				parent::__construct($ID, "download_categories");
		}
		
		public static function Create($title) {
			$c = Bundle\DB::Connect();
			$c->query("INSERT INTO " . DB_PREFIX . "download_categories (Title) VALUES ('" . $title . "')");
			return $c->insert_id;
		}
	}
