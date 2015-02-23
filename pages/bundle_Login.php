<!DOCTYPE html>
<html>
    <head>
        <title><?= HLoc::l("Login") ?></title>
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
                header("location: ./administration");
        ?>
    </head>
    <body>
        <div id="login">
            <form method="POST" action="./administration">
                <?= HToken::html() ?>
                <content>
                    <h1 class="page-header">BUNDLE</h1>
                    <?php if (@(new Bundle\GetParser)->error !== null): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= HLoc::l("Login") . " <strong>" . HLoc::l("failed") . "</strong>!" ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-user pull-right"></span></div>
                            <input type="text" class="form-control" id="exampleInputAmount" name="name" placeholder="<?= HLoc::l("username") ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-option-horizontal pull-right"></span></div>
                            <input type="password" class="form-control" id="exampleInputAmount" name="pass" placeholder="<?= HLoc::l("password") ?>">
                        </div>
                    </div>
                    <input type="submit" class="btn btn-block btn-primary" value="<?= HLoc::l("Log in") ?>" name="login" />
                    
                    <br / >

                    <div class="well">
                        <div class="col-md-6">
                            <a href="register" class="btn btn-success btn-block"><?= HLoc::l("Register") ?></a>
                        </div>
                        <div class="col-md-6">
                            <a href="./" class="btn btn-danger btn-block"><?= HLoc::l("Back") ?></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </content>
            </form>
        </div>
    </body>
</html>
