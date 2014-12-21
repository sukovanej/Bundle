<!DOCTYPE html>
<html>
    <head>
        <title>Přihlášení</title>
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
            <form method="POST" action="./administrace">
                <h1>Přihlásit se</h1>
                <content>
                <p><img src="./images/login_username.png" /><input type="text" name="name" /></p>
                <p><img src="./images/login_password.png" /><input type="password" name="pass" /></p>
                <input type="submit" value="Přihlásit" name="login" />
                </content>
            </form>
        </div>
    </body>
</html>
