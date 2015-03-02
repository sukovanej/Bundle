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
			$this->Username = HLoc::l("Anonymous");
			$this->Role = -1;
		} else {
			parent::__construct($ID, "users");
		}
		
		$this->Roles = array(
			"0" => \HLoc::l("Administrator"),
			"1" => \HLoc::l("Editor"),
			"2" => \HLoc::l("User"),
			"-1" => \HLoc::l("Anonymous")
		);
					
		$this->RoleString = $this->Roles[$this->Role];
		
		if (empty($this->Photo))
			$this->Photo = "./upload/users/no-photo.png";
    }
    
    public static function InstByUsername($name) {
		$connect = DB::Connect();
		
			$name = $connect->real_escape_string($name);
			
		$id = $connect->query("SELECT ID FROM " . DB_PREFIX . "users WHERE Username = '" . $name . "'")->fetch_object()->ID;
		return new User($id);
	}
    
    public function InstUpdate() {
        parent::InstUpdate();
        $this->RoleString = $this->Roles[$this->Role];
    }
    
    public static function Create($username, $password, $email) {
		$connect = DB::Connect();
		
			$username = htmlspecialchars($connect->real_escape_string($username));
			$email = htmlspecialchars($connect->real_escape_string($email));
			$password = $connect->real_escape_string($password);
		
		$connect->query("INSERT INTO " . DB_PREFIX . "users (Username, Password, Email, Role) VALUES ('"
                . $username . "', '" . sha1($password) . "', '" . $email . "', 2)");
        $id = $connect->insert_id;
        
        return new User($id);
    }
    
    public static function Exists($name, $value) {
        $connect = DB::Connect();
        
        if (is_string($value))
            $value = "'" . $connect->real_escape_string($value) . "'";
        
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
		$user->Username = HLoc::l("Anonymous");
		$user->Role = 3;
		
		return $user;
	}
}
