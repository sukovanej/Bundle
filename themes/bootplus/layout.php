<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= $Page->Name ?> | <?= $Page->Actual ?></title>
        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Bundle CMS">
        <meta name="author" content="Milan Suk">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <link href="<?= $Page->ThemeRoot ?>/css/bootplus.min.css" rel="stylesheet">
        <link href="<?= $Page->ThemeRoot ?>/css/style.css?get=<?= date("s") ?>" rel="stylesheet">
        <link href="<?= $Page->ThemeRoot ?>/css/bootplus-responsive.min.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="<?= $Page->Icon ?>?get=<?= date("s") ?>" />
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="js/html5shiv.js"></script>
        <![endif]-->
        <?php $Page->Head() ?>
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid navbar-bundle">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="./"><?= $Page->Name ?></a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <?php foreach($Page->Menu as $Menu): ?>
                                <li class="<?= $Menu->Current ?>"><a href="<?= $Menu->Url ?>"><?= $Menu->Title ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container-fluid container-bundle">
            <div class="row-fluid">
                <div class="span3">
                    <div class="sidebar-nav">
                        <ul class="nav nav-list">
                            <?php if (!empty($router)) { $Url = Bundle\Url::InstByUrl($router); }?>
                            <?php if(isset($Url) && $Url->Type == "page"): ?>
                            <?php $this_page = new Bundle\Page($Url->Data); ?>
                                <?php
                                    if ($this_page->Parent == 0) {
                                        $sub = $this_page->Children();
                                    } else {
                                        $sub = (new Bundle\Page($this_page->Parent))->Children();
                                    }
                                ?>
                                <?php if (count($sub) > 0): foreach($sub as $page): ?>
                                    <?php if ($Url->Data == $page->ID) $current = "active"; else $current = ""; ?>
                                    <li class="<?= $current ?>"><a href="<?= $page->Url ?>"><?= $page->Title ?></a></li>
                                <?php endforeach; else: ?>
                                    <li><a href="#">Žádná podstránka</a></li>
                                <?php endif; ?>
                            <?php else: ?>
                                <li><a href="#">Žádná podstránka</a></li>
                            <?php endif; ?>
                        </ul>
                    </div><!--/.well -->
                </div><!--/span-->

                <div class="span9">
                    <?php if($Url->Type == "home" && HConfiguration::get("bootstrap_theme_config_show_jumbotron") != -1): ?>
                    <div class="hero-unit">
                        <h1><?= HConfiguration::get("bootstrap_theme_config_show_jumbotron_title") ?></h1>
                        <?= HConfiguration::get("bootstrap_theme_config_show_jumbotron_text") ?>
                    </div>
                    <?php endif; ?>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="card">
                                <div class="card-body">
                                <?php $Page->Content() ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div><!--/span-->
            </div><!--/row-->
            <hr>
            <footer>
                <?php $Page->Footer() ?>
                <p>&copy; 2014 - <?= date("Y") ?>. Powered by <a href="http://bundle-cms.cz/">Bundle</a>. 
                    <a href="./administration"><?= HLoc::l("Administration") ?></a></p>
            </footer>
        </div>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
