<?php

if (count(explode("-", $router)) >= 2) {
    $routers = explode("-", $router);
    unset($routers[0]);
    $subrouter = implode("-", $routers);
} else {
    $subrouter = "null";
}

if ($subrouter == "logout") {
    session_destroy();
    Bundle\Events::Execute("LogOut");
    echo "<p><a href='./'>" . HLoc::l("Homepage") . "</a></p>";
    header("location: ./");
    die();
}

if (!isset($_SESSION["user"])) {
    if (isset($_POST["login"])) {
        $name = $_POST["name"];
        $password = $_POST["pass"];

        $connect = Bundle\DB::Connect();
			$name = htmlspecialchars($connect->escape_string($name));
			$password = htmlspecialchars($connect->escape_string($password));

        $q = $connect->query("SELECT ID FROM " . DB_PREFIX . "users WHERE Username = '" . htmlspecialchars($name) . "' AND Password = '" . 
            sha1($password) . "'");

        $ID = $q->fetch_assoc()["ID"];
        
        if ($ID >= 1) {
            $_SESSION["user"] = $ID;
            $User = new Bundle\User($_SESSION["user"]);
            Bundle\Events::Execute("LogIn");
        } else {
            header("location: ./login?error");
            echo "<p><a href='./'>" . HLoc::l("Homepage") . "</a></p>";
            die();
        }
    } else {
		echo "<p><a href='./'>" . HLoc::l("Homepage") . "</a></p>";
        header("location: ./login");
        die();
    }
}
