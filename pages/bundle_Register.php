<!DOCTYPE html>
<html>
    <head>
        <title>Registrace</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="icon" type="image/png" href="./images/icon.png?get=iasdfsdfco" />
        <link rel="stylesheet" href="./admin/reset.css" />
        <!-- Bootstrap core CSS -->
        <link href="admin/css/bootstrap.min.css" rel="stylesheet">
        <link href="admin/css/bootstrap-theme.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./pages/style.css" />
        <?php
            if (Bundle\User::IsLogged())
                header("location: ./administrace");
        ?>
    </head>
    <body>
        <div id="login">
        <?php if($Page->AllowRegister == 1): ?>
            <form method="POST">
                <?= HToken::html() ?>
            	<content>
					<h1 class="page-header">BUNDLE</h1>            
					<?php
						function __post($var) { // for saving input values when the was some error
							return isset($_POST[$var]) ? $_POST[$var] : null;
						}
						
						if(isset($_POST["register"])) {
							if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["pass"])
									|| empty($_POST["pass_rep"])) { // when some information are missing
								echo("<div class='alert alert-danger' role='alert'>" . HLoc::l("You must complete all fields") . ".</div>");
							} else if ($_POST["pass"] != $_POST["pass_rep"]) { // when first and second password aren't equal
								echo("<div class='alert alert-danger' role='alert'>" . HLoc::l("The passwords must match") . ".</div>");
							} else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) { // email has to have a standard format {mail}@{domain}.{tld}
								echo("<div class='alert alert-danger' role='alert'>" . HLoc::l("The email has an incorrect format") . ".</div>");
							} else if (Bundle\User::Exists("Email", $_POST["email"])) { // selected email already exist
								echo("<div class='alert alert-danger' role='alert'>" . HLoc::l("The email is already used") . ".</div>");
							} else if (Bundle\User::Exists("Username", $_POST["name"])) { // selected username already exist
								echo("<div class='alert alert-danger' role='alert'>" . HLoc::l("The username is already used") . ".</div>");
							} else { // everything's OK 
								Bundle\User::Create($_POST["name"], $_POST["pass"], $_POST["email"]);


                                /*
                                 * Send an email
                                 * =======================================================================================================
                                 */

                                    $email = new HEmail;
                                    $email->To = $_POST["email"];
                                    $email->Subject = HLoc::l("Your account has been created") . " (" . HConfiguration::get("BaseURL") . ")";
                                    $email->Message = Hloc::l("Thank you for registering on our website") . ". <br />" 
                                        . Hloc::l("Your login details are") . ": <br /><ul><li>" . HLoc::l("username") 
                                        . ": <strong>" . $_POST["name"] . "</strong></li><li>" . HLoc::l("password") . ": <strong>" 
                                        . substr($_POST["pass"], strlen($_POST["pass"]) - 2, $_POST["pass"] - 1) . "*****</strong></li></ul>";

                                /*
                                 * =======================================================================================================-
                                 */

								echo ("<div class='alert alert-success' role='alert'>" . HLoc::l("Your account has been created") . ". " 
                                    . HLoc::l("Now you can") . " <a href='./login'>" . HLoc::l("log in") . "</a>.</div>");
							}
						}
					?>
					<div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-user pull-right"></span></div>
                            <input type="text" class="form-control" id="exampleInputAmount" name="name" placeholder="<?= HLoc::l("username") ?>" value="<?= __post("name") ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><strong style="font-size:16px">@</strong></span></div>
                            <input type="text" class="form-control" id="exampleInputAmount" name="email" placeholder="<?= HLoc::l("email") ?>" value="<?= __post("email") ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-option-horizontal pull-right"></span></div>
                            <input type="password" class="form-control" id="exampleInputAmount" name="pass" placeholder="<?= HLoc::l("password") ?>" value="<?= __post("pass") ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-option-horizontal pull-right"></span></div>
                            <input type="password" class="form-control" id="exampleInputAmount" name="pass_rep" placeholder="<?= HLoc::l("password again") ?>" value="<?= __post("pass_rep") ?>">
                        </div>
                    </div>
					<input type="submit" class="btn btn-block btn-primary" value="<?= HLoc::l("Register") ?>" name="register" />
	                                   
                    <br / >

                    <div class="well">
                        <div class="col-md-6">
                            <a href="login" class="btn btn-success btn-block"><?= HLoc::l("Log in") ?></a>
                        </div>
                        <div class="col-md-6">
                            <a href="./" class="btn btn-danger btn-block"><?= HLoc::l("Back") ?></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>

            	</content>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">
                <?= HLoc::l("Registration is now allowed at this moment") ?>, <a href="./"><?= HLoc::l("return to homepage") ?></a>.
            </div>
        <?php endif; ?>
        </div>
    </body>
</html>
