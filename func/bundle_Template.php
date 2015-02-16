<?php

/**
 * Config
 *
 * @author sukovanej
 */

namespace Bundle; 

class Template {
	private $connect;
	
    public function __construct($e = 0) {
        $this->connect = DB::Connect();
        $this->PagerInt = 0;
        
        $re = $this->connect->query("SELECT * FROM " . DB_PREFIX . "config");
        
        while ($row = $re->fetch_object())
			$this->{$row->Name} = $row->Value;
        
		if (isset($_POST["pager"]))
			$this->PagerInt = $_POST["pager"];
		else if (isset($_GET["pager"]))
			$this->PagerInt = $_GET["pager"];
            
        if ($e == 0) {
			$_menu = new Menu();
            $this->Menu = $_menu->Menu();
		}
		
        $this->ThemeRoot = "./themes/" . $this->Theme;
        $this->Bundle = "1.2.1"; // systém verzování = [větev].[verze].[sub-verze].[oprava]
        
        /*
         * Poslední oprava:
         * 12.01.2015
         * sukovanej 
         */
    }
    
    public function Update($name, $value) {
        if (is_string($value))
            $value = "'" . $this->connect->escape_string($value) . "'";
        
        $this->connect->query("UPDATE " . DB_PREFIX . "config SET Value = " . $value . " WHERE Name = '" . $name . "'");
    }
    
    public function InstUpdate() {
        $re = $this->connect->query("SELECT * FROM " . DB_PREFIX . "config");
        
        while ($row = $re->fetch_object())
			$this->{$row->Name} = $row->Value;
    }
    
    // Content 
    
    public function MainContent() {
		$router = @$_GET["router"];
		
		if (empty($router)) {
			if ($this->Homepage == 0)
				$this->ArticlesGen();
			else
				$this->PageGen($this->Homepage);
		} else {
			$Url = Url::InstByUrl($router);
			
			if (!$Url) {
				if (file_exists("themes/" . $this->Theme . "/error.php"))
					require("themes/" . $this->Theme . "/error.php");
				else
					require("func/defaults/error.php");
			} else {
				if ($Url->Type == "category") {
					$this->ArticlesByCategoryGen($Url->Data);
				} else if ($Url->Type == "article") {
					$this->ArticleGen($Url->Data);
				} else if ($Url->Type == "page") {
					$this->PageGen($Url->Data);
				} else {
					$package = new Package($Url->Data);
					
					if ($package->IsActive) {
						$package->Generate();
					} else {
						if (file_exists("themes/" . $this->Theme . "/error.php"))
							require("themes/" . $this->Theme . "/error.php");
						else
							require("func/defaults/error.php");
					}
				}
			}
		}
    }
    
    public function PackageContent($id) {
		$package = new Package($id);
		if ($package->IsActive)
			$package->Generate();
	}
	
	public function Head() {
		Events::Execute("Head");
	}
	
	// Header
    
    public function Header($place = "header") {
		$items = Content::ListByPlace($place);
		
		Events::Execute("BeforeHeader");
		
		if ($items != false)
			foreach($items as $item){
				
				Events::Execute("BeforeEachHeader");
				
				if ($item->Type == "main") {
					$this->MainContent();
				} else if ($item->Type == "package") {
					$this->PackageContent($item->Data);
				}
				
				Events::Execute("AfterEachHeader");
			}
			
		Events::Execute("AfterHeader");
	}

	// navigation

	public function Navigation($type = "default") {
		global $Page, $User;
		require_once("func/defaults/navigation.php");
	}
    
    // Content
    
    public function Content($place = "content") {
		$items = Content::ListByPlace($place);
		
		Events::Execute("BeforeContent");
		
		if ($items != false)
			foreach($items as $item){
				
				Events::Execute("BeforeEachContent");
				
				if ($item->Type == "main") {
					$this->MainContent();
				} else if ($item->Type == "package") {
					$this->PackageContent($item->Data);
				}
				
				Events::Execute("AfterEachContent");
			}
			
		Events::Execute("AfterContent");
	}
    
    // Panel
    
    public function Panel($place = "panel") {
		$items = Content::ListByPlace($place);
		
		Events::Execute("BeforePanel");
		
		if ($items != false)
			foreach($items as $item){
				
				Events::Execute("BeforeEachPanel");
				
				$this->PackageContent($item->Data);
				
				Events::Execute("AfterEachPanel");
			}
			
		Events::Execute("AfterPanel");
	}
	
	// Footer
	
	public function Footer($place = "footer") {
		$items = Content::ListByPlace($place);
		
		Events::Execute("BeforeFooter");
		
		if ($items != false)
			foreach($items as $item){
				
				Events::Execute("BeforeEachFooter");
				
				if ($item->Type == "main") {
					echo($this->Footer);
				} else if ($item->Type == "package") {
					$this->PackageContent($item->Data);
				}
				
				Events::Execute("AfterEachFooter");
			}
			
		Events::Execute("AfterFooter");
	}
    
    // Content methods
    
    private function ArticlesGen() {
        $connect = DB::Connect();
        $Page = $this;
        
        $_r = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "articles WHERE ShowInView = 1 AND Status = 1")->fetch_object();
        $this->PagerPages = $_r->Count / $this->PagerMax;
        
        if (round($this->PagerPages) > $this->PagerPages)
			$this->PagerPages = round($this->PagerPages);
		else if (round($this->PagerPages) < $this->PagerPages)
			$this->PagerPages = round($this->PagerPages) + 1;
        
        $result = $connect->query("SELECT ID, Author FROM " . DB_PREFIX . "articles WHERE ShowInView = 1 AND Status = 1 ORDER BY Datetime DESC LIMIT " 
			. ($this->PagerInt * $this->PagerMax) . ", " . $this->PagerMax);

        if ($result->num_rows == 0)
        	if (file_exists("themes/" . $this->Theme . "/article_null.php"))
				require("themes/" . $this->Theme . "/article_null.php");
			else
				require("func/defaults/article_null.php");
            
        while($row = $result->fetch_assoc()) {
            $Article = new Article($row["ID"]);
            $Author = new User($row["Author"]);
            
            if (file_exists("themes/" . $this->Theme . "/article.php"))
				require("themes/" . $this->Theme . "/article.php");
			else
				require("func/defaults/article.php");
        }
        
        if (file_exists("themes/" . $this->Theme . "/pager.php"))
			require("themes/" . $this->Theme . "/pager.php");
		else
			require("func/defaults/pager.php");
    }
    
    private function ArticlesByCategoryGen($category) {
        $connect = DB::Connect();
        $Page = $this;
        
        $result = $connect->query("SELECT Article FROM " . DB_PREFIX . "article_categories WHERE Category = " . $category  
			. " ORDER BY ID LIMIT " . ($this->PagerInt * $this->PagerMax) . ", " . $this->PagerMax);
                
        $_r = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "article_categories WHERE Category = " . $category)->fetch_object();
        $this->PagerPages = $_r->Count / $this->PagerMax;
        
        if (round($this->PagerPages) > $this->PagerPages)
			$this->PagerPages = round($this->PagerPages);
		else if (round($this->PagerPages) < $this->PagerPages)
			$this->PagerPages = round($this->PagerPages) + 1;
			
        $i = 0;
            
        while($row = $result->fetch_object()) {
            $Article = new Article($row->Article);
            
            if ($Article->ShowInView && $Article->Status == 1) {
				$Author = new User($Article->Author);
				
				if (file_exists("themes/" . $this->Theme . "/article.php"))
					require("themes/" . $this->Theme . "/article.php");
				else
					require("func/defaults/article.php");

				$i++;
			}
        }
        
		if ($i == 0) {
			if (file_exists("themes/" . $this->Theme . "/article_null.php"))
				require("themes/" . $this->Theme . "/article_null.php");
			else
				require("func/defaults/article_null.php");
		} else {
			if (file_exists("themes/" . $this->Theme . "/pager.php"))
				require("themes/" . $this->Theme . "/pager.php");
			else
				require("func/defaults/pager.php");
		}
    }
    
    private function PageGen($ID) {
        $Page = new Page($ID);
        Events::Execute("Page", array(&$Page));

        if (file_exists("themes/" . $this->Theme . "/page_single.php"))
        	require("themes/" . $this->Theme . "/page_single.php");
        else
        	require("func/defaults/page_single.php");
    }
    
    private function ArticleGen($ID) {
        $Article = new Article($ID);
        $Author = new User($Article->Author);
        $Page = new Template;
        
        // Add new comment
        
        if (isset($_POST["bundle_comment_submit"]) && $Article->AllowComments && $this->AllowComments) {
            $text = $_POST["bundle_comment_text"];
            
			if (!empty($_SERVER['HTTP_CLIENT_IP']))
				$ip=$_SERVER['HTTP_CLIENT_IP'];
			else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			else
				$ip=$_SERVER['REMOTE_ADDR'];
            
            $author = -1;
            if (User::IsLogged())
                $author = (new User($_SESSION["user"]))->ID;
            
            if (!empty($text) && (($author == -1 && $this->AllowUnregistredComments) || $author != -1)) {
                Comment::Create($text, $ID, $author, $ip);
                Events::Execute("CommentSubmit", array($author, $ip));
            } else {
                Events::Execute("CommentSubmitError");
            }
        }

        if (file_exists("themes/" . $this->Theme . "/article_single.php")) {
        	require("themes/" . $this->Theme . "/article_single.php");
        } else {
			require("func/defaults/article_single.php");
        }

    }
}
