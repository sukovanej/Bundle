<?php

/**
 * Bundle\User
 *
 * @author sukovanej
 */
 
namespace Bundle; 
 
class User extends DatabaseBase{
    public function __construct($ID) {
		$this->ID = $ID;
		
        if ($this->ID == -1) {
			$this->Username = "Anonymní uživatel";
			$this->Role = -1;
		} else {
			parent::__construct($ID, "users");
		}
		
		$this->Roles = array(
			"0" => "Administrátor",
			"1" => "Redaktor",
			"2" => "Uživatel",
			"-1" => "Anonym"
		);
					
		$this->RoleString = $this->Roles[$this->Role];
		
		if (empty($this->Photo))
			$this->Photo = "./upload/users/no-photo.png";
    }
    
    public function InstByUsername($name) {
		$id = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "users WHERE Username = '" . $name . "'")->fetch_object()->ID;
		return new User($id);
	}
    
    public function InstUpdate() {
        parent::InstUpdate();
        $this->RoleString = $this->Roles[$this->Role];
    }
    
    public static function Create($username, $password, $email) {
		$connect = DB::Connect();
		$connect->query("INSERT INTO " . DB_PREFIX . "users (Username, Password, Email, Role) VALUES ('"
                . $username . "', '" . sha1($password) . "', '" . $email . "', 2)");
        $id = $connect->insert_id;
        $connect->close();
        
        return new User($id);
    }
    
    public static function Exists($name, $value) {
        $connect = DB::Connect();
        
        if (is_string($value))
            $value = "'" . $connect->escape_string($value) . "'";
        
        return ($connect->query("SELECT * FROM " . DB_PREFIX . "users WHERE " . $name . " = " . $value)->num_rows > 0);
    }
    
    public static function IsLogged() {
        return isset($_SESSION["user"]);
    }
    
    public static function GetList($role = -1) {
		$connect = DB::Connect();
		
		if($role == -1)
			$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "users");
		else
			$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "users WHERE Role = " . $role);		
			
		$result = array();
		
		while ($row = $re->fetch_object())
			$result[] = new User($row->ID);
			
		return $result;
	}
    
	public static function Count($role = -1) {
		return count(self::GetList($role));
	}
    
    public static function CurrentUser() {
		if (self::IsLogged()) 
			return new User($_SESSION["user"]);
		else
			return self::Anonymous();
	}
	
	public static function Anonymous() {
		$user = new \stdclass();
		$user->ID = -1;
		$user->Username = "Anonymní uživatel";
		$user->Role = 3;
		
		return $user;
	}
}
