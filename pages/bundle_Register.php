<!DOCTYPE html>
<html>
    <head>
        <title>Registrace</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="./pages/style.css" />
        <link rel="stylesheet" href="./admin/reset.css" />
        <?php
            if (Bundle\User::IsLogged())
                header("location: ./administrace");
        ?>
    </head>
    <body>
        <div id="login">
        <?php if($Page->AllowRegister == 1): ?>
            <form method="POST">
				<h1>Registrovat</h1>            
				<?php
					function __post($var) {
						return isset($_POST[$var]) ? $_POST[$var] : null;
					}
					
					if(isset($_POST["register"])) {
						if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["pass"])
								|| empty($_POST["pass_rep"])) {
							echo "<div id='error'>Všechny údaje musí být vyplněné.</div>";
						} else if ($_POST["pass"] != $_POST["pass_rep"]) {
							echo "<div id='error'>Zadaná hesla se musí shodovat.</div>";
						} else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
							echo "<div id='error'>Email je zadán ve špatném tvaru.</div>";
						} else if (Bundle\User::Exists("Email", $_POST["email"])) {
							echo "<div id='error'>Zadaný email už je použitý.</div>";
						} else if (Bundle\User::Exists("Username", $_POST["name"])) {
							echo "<div id='error'>Zadané uživatelské jméno už je použité.</div>";
						} else {
							Bundle\User::Create($_POST["name"], $_POST["pass"], $_POST["email"]);
							echo "<div id='done'>Váš uživatelský účet byl úspěšně vytvořen nyní se "
							. "můžete <a href='./prihlaseni'>přihlásit</a> do webového administračního rozhraní.</div>";
						}
					}
				?>
				<content>
					<p title="Uživatelské jméno"><img src="./images/login_username.png" /><input type="text" name="name" value="<?= __post("name") ?>" /></p>
					<p title="Email"><img src="./images/login_email.png" style="width:17px; height:18px; padding:6px 7px 6px 6px;" /><input type="text" name="email" value="<?= __post("email") ?>" /></p>
					<p title="Heslo"><img src="./images/login_password.png" /><input type="password" name="pass" value="<?= __post("pass") ?>" /></p>
					<p title="Heslo znovu"><img src="./images/login_password_rep.png" style="width:17px; height:18px; padding:6px 7px 6px 6px;" /><input type="password" name="pass_rep" value="<?= __post("pass_rep") ?>" /></p>
					<input type="submit" value="Registrovat" name="register" />
				</content>
                <p><a href="./">Zpět na stránku</a></p>
            </form>
        <?php else: ?>
            <p>Registrace není v současné době povolena. <br /> Vraťte se, prosím, 
                <a href="./">zpět na stránky</a>.</p>
        <?php endif; ?>
        </div>
    </body>
</html>
