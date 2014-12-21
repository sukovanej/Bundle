<?php
	class bundle_Path extends Bundle\PackageBase {
		public function __construct() {
			$this->includes = array();
			
			parent::__construct();
		}
		
		public static function Generate() {
			require("layout.php");
		}
		
		public static function path_string() {
			$out = "<a href='./'>Úvod</a>";
			
			$router = @$_GET["router"];
			if (Bundle\Url::IsDefinedUrl($router)) {
				$Url = Bundle\Url::InstByUrl($router);
				
				if ($Url->Type == "article")
					$out .= " &rarr; " . (new Bundle\Article($Url->Data))->CategoriesString . " &rarr; <a href='" . (new Bundle\Article($Url->Data))->Url . "'>" . (new Bundle\Article($Url->Data))->Title . "</a>";
				else if ($Url->Type == "page")
					$out .= " &rarr; <a href='" . (new Bundle\Page($Url->Data))->Url . "'>" . (new Bundle\Page($Url->Data))->Title ."</a>";
				else if ($Url->Type == "category")
					$out .= " &rarr; <a href='" . (new Bundle\Category($Url->Data))->Url . "'>" . (new Bundle\Category($Url->Data))->Title . "</a>";
				else if ($Url->Type == "package")
					$out .= " &rarr; <a href='" . (new Bundle\Package($Url->Data))->Url . "'>" . (new Bundle\Package($Url->Data))->Title . "</a>";
			}
			
			$out = str_replace("&rarr;  &rarr;", "&rarr; [<em>nezařazeno</em>]  &rarr;", $out);
			
			return $out;
		}
	}
