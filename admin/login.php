<?php

if (count(explode("-", $router)) >= 2) {
    $routers = explode("-", $router);
    unset($routers[0]);
    $subrouter = implode("-", $routers);
} else {
    $subrouter = "null";
}

if ($subrouter == "odhlasit") {
    session_destroy();
    Bundle\Events::Execute("LogOut");
    echo "<p>Odhlášení proběhlo úspěšně, nepodařilo se všat přesměrovat stránku na úvodní stránku webu. Prověďte tak prosím ručně.</p>";
    header("location: ./");
    die();
}

if (!isset($_SESSION["user"])) {
    if (isset($_POST["login"])) {
        $name = $_POST["name"];
        $password = $_POST["pass"];

        $connect = Bundle\DB::Connect();
			$name = $connect->escape_string($name);
			$password = $connect->escape_string($password);

        $q = $connect->query("SELECT ID FROM " . DB_PREFIX . "users WHERE Username = '" . $name . "' AND Password = '" . 
            sha1($password) . "'");

        $ID = $q->fetch_assoc()["ID"];
        
        if ($ID >= 1) {
            $_SESSION["user"] = $ID;
            $User = new Bundle\User($_SESSION["user"]);
            Bundle\Events::Execute("LogIn");
        } else {
            header("location: ./prihlaseni");
            echo "<p>Nepodařilo se přesměrovat stránku na přihlašovací stránku. Prověďte tak prosím ručně zadaním přidáním <em>/administrace</em> za doménu
            stránky</p>";
            die();
        }
    } else {
		echo "<p>Nepodařilo se přesměrovat stránku na přihlašovací stránku. Prověďte tak prosím ručně zadaním přidáním <em>/administrace</em> za doménu
            stránky</p>";
        header("location: ./prihlaseni");
        die();
    }
}
