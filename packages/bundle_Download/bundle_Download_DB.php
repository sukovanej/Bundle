<?php
	class bundle_Download_DB {
		public function __construct() {
			$this->connect = Bundle\DB::Connect();
		}
		
		public static function Create($title, $filename, $descrip, $category = -1) {
			$connect = Bundle\DB::Connect();
			$connect->escape_string($title);
			$connect->escape_string($filename);
			$connect->escape_string($descrip);
			$connect->query("INSERT INTO " . DB_PREFIX . "download (Title, Filename, Description, Category) VALUES ('" . $title . "', '" . $filename . "', '" 
				. $descrip . "', " . $category . ")");
			$id = $connect->insert_id;
			$connect->close();
			
			return $id;
		}
		
		public static function get_files($category = -1) {
			$connect = Bundle\DB::Connect();
			
			if ($category == -1)
				$result = $connect->query("SELECT ID, Filename FROM " . DB_PREFIX . "download ORDER BY Datetime DESC");
			else
				$result = $connect->query("SELECT ID, Filename FROM " . DB_PREFIX . "download WHERE Category = " . $category . " ORDER BY Datetime DESC");
			
			$array = array();
			 
			while($row = $result->fetch_object())
				if(file_exists(getcwd() . "/upload/" . $row->Filename))
					$array[] = new bundle_Download_File_DB($row->ID);
			 
			return $array;
		}
		
		public static function Count() {
			$re = Bundle\DB::Connect()->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "download")->fetch_object();
			return $re->Count;
		}
		
		public static function CreateCategory($title) {
			$id = bundle_Download_category_DB::Create($title);
		}
		
		public static function GetCategories() {
			$cats = Bundle\DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "download_categories");
			$result = array();
			
			while($c = $cats->fetch_object()) {
				$result[] = new bundle_Download_category_DB($c->ID);
			}
				
			return $result;
		}
	}
	
	class bundle_Download_File_DB extends Bundle\DatabaseBase {
		public function __construct($ID) {
			parent::__construct($ID, "download");
			$info = pathinfo($this->Filename);
			$this->Type = self::get_type($info["extension"]);
			$this->CategoryObj = new bundle_Download_category($this->Category);
		}
		
		public static function get_type($type) {
			$types = array(
				"mp3" => "audio",
				"mp4" => "video", "3gp" => "video",
				"jpg" => "image", "png" => "image", "bmp" => "image", "gip" => "image",
				"pdf" => "pdf",
				"docx"=> "word", "doc" => "word", "odt" => "word"
			);
			
			return $types[$type];
		}
	}
