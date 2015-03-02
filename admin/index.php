<?php $generated_time = explode(' ', microtime()); // Vykreslovací čas ?>
<?php $generated_time = $generated_time[1] + $generated_time[0]; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php header("X-Frame-Options: Deny"); ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/png" href="./images/icon.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <script type="text/javascript" src="admin/jquery.js"></script>

        <title><?= HLoc::l("Administration") ?></title>

        <!-- Bootstrap core CSS -->
        <link href="admin/css/bootstrap.min.css" rel="stylesheet">
        <link href="admin/css/bootstrap-theme.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="admin/css/dashboard.css" rel="stylesheet">
        <script type="text/javascript" src="./admin/js.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="admin/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
        <?php Bundle\Events::Execute("AdminHead") ?>
        <?php 
            require("login.php");
            require("admin.php");
        ?>
    </head>

    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only"><?= HLoc::l("Toggle navigation") ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="./">Bundle</a>
                </div>
                <?php if ($User->Role < 2): ?>
                <div class="nav-header-creates">
                    <a href="administration-create-article" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> 
                        <?= HLoc::l("New article") ?></a>
                    <a href="administration-create-page" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> 
                        <?= HLoc::l("New page") ?></a>

                    <?php Bundle\Events::Execute("AdminLeftTopNav") ?>  
                </div>
                <?php endif; ?>
                <div id="navbar" class="navbar-collapse collapse">
                    <?php Bundle\Events::Execute("AdminRightTopNav") ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="administration"><?= HLoc::l("Dashboard") ?>
                            <span class="pull-right glyphicon glyphicon-home mobile-only"></span></a></li>
                        <li class="desktop-only"><a href="administration-config"><span class="glyphicon glyphicon-cog"></span></a></li>
                        <li class="mobile-only"><a href="administration-config"><?= HLoc::l("Configuration") ?>
                            <span class="pull-right glyphicon glyphicon-cog"></span></a></li>
                        <li><a href="administration-profile"><?= HLoc::l("Profile") ?>
                            <span class="pull-right glyphicon glyphicon-user mobile-only"></span></a></li>
                        <li class="mobile-only"><a href="administration-adminnav"><?= HLoc::l("Navigation") ?>
                            <span class="pull-right glyphicon glyphicon-list"></span></a></li>
                        <li><a href="#"><strong><?= $User->Username ?></strong></a></li>
                        <li><a href="administration-logout"><span class="btn btn-warning btn-xs btn-nav"><?= HLoc::l("Log out") ?></span></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php if ($User->Role < 2): ?>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <?php if ($User->Role <= 1): ?>
                <ul class="nav nav-sidebar"> 
                    <li><a href="administration-articles"><?= HLoc::l("Articles") ?> <span class="badge pull-right"><?= Bundle\Article::CountAll() ?></span></a></li>
                    <li><a href="administration-comments"><?= HLoc::l("Comments") ?> <span class="badge pull-right"><?= Bundle\Comment::CountAll() ?></span></a></li>   
                    <li><a href="administration-pages"><?= HLoc::l("Pages") ?> <span class="badge pull-right"><?= Bundle\Page::CountAll() ?></span></a></li> 
                </ul>
                <?php endif; if ($User->Role == 0): ?>
                <ul class="nav nav-sidebar">     
                    <li><a href="administration-users"><?= HLoc::l("Users") ?> <span class="glyphicon glyphicon-user pull-right"></span></a></li> 
                    <li><a href="administration-categories"><?= HLoc::l("Categories") ?> <span class="glyphicon glyphicon-list-alt pull-right"></span></a></li> 
                    <li><a href="administration-packages"><?= HLoc::l("Packages") ?> <span class="glyphicon glyphicon-star pull-right"></span></a></li> 
                </ul>
                <ul class="nav nav-sidebar">     
                    <?php if(file_exists($Page->ThemeRoot . "/admin.php")): ?> 
                    <li><a href="administration-template"><?= HLoc::l("Template") ?> <span class="glyphicon glyphicon-eye-open pull-right"></span></a></li>
                    <?php endif; ?>
                    
                    <li><a href="administration-content"><?= HLoc::l("Content generating") ?> <span class="glyphicon glyphicon-hdd pull-right"></span></a></li>
                    <li><a href="administration-navigation"><?= HLoc::l("Navigation") ?> <span class="glyphicon glyphicon-list pull-right"></span></a></li>
                    
                    <?php endif; ?>
                </ul>
                <div id="clear-nav"></div>
                <?php if ($User->Role <= 1): ?>
                    <ul class="nav nav-sidebar">
                        <?php if ($User->Role == 0): ?>
                            <?php foreach(Bundle\Packages::get_packages_admin_menu() as $package): ?>
                            <li><a href="./administration-package-<?= $package->Name ?>" title="<?= $package->Name ?>">
                                <?= $package->Config->name ?><img src="<?= $package->IconUrl ?>" class="pull-right" /></a></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
                <ul>
                    <?php Bundle\Events::Execute("AdminNav") ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class=" <?php if ($User->Role < 2): ?>col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2<?php endif; ?> main">

                <?php
                    // check token
                    if ($_SERVER['REQUEST_METHOD'] == "POST" && !HToken::checkToken()) {
                        Admin::ErrorMessage(HLoc::l("Bad token! Try it again") . ".");
                        die();
                    }
                    /* 
                     * 0 = administrator
                     * 1 = editor
                     * 2 = user
                     * 3 = anonymous 
                     */

                    try {
                        $pages = array(
                            "config" => "configpage;0",
                            "profile" => "profile;2",
                            "navigation" => "menu;0",
                            "content" => "configpage_theme;0",
                            "users" => "users;0",
                            "create-page" => "create_page;0",
                            "pages" => "pages;0",
                            "edit-page;s" => "edit_page;0",
                            "categories" => "categories;0",
                            "create-article" => "create_article;1",
                            "articles" => "articles;1",
                            "edit-article;s" => "edit_article;1",
                            "comments" => "comments;0",
                            "packages" => "packages;0",
                            "package;s" => "package;2",
                            "install-package;s" => "install_package;0",
                            "uninstall-package;s" => "uninstall_package;0",
                            "template" => "theme_admin;0",
                            "adminnav" => "adminnav;0"
                        );
                        
                        /* 
                         * Vykreslování obsahu administration, NEUPRAVOVAT!
                         */
                        
                        $bool_show = false;
                        foreach($pages as $page => $file) {
                            $page_c = preg_split("[;]", $page);
                            $file_c = preg_split("[;]", $file);
                            
                            if ($User->Role <= $file_c[1]) {
                                if ((count($page_c) == 2 && $page_c[1] == "s" && HString::startsWith($subrouter, $page_c[0])) || ($page_c[0] == $subrouter)) {
                                    require($file_c[0] . ".php");
                                    $bool_show = true;
                                    break;
                                }
                            }
                        }
                        
                        if (!$bool_show)
                            require("info.php");
                    } catch (Exception $e) {
                        Admin::ErrorMessage(HLoc::l("Error") . ": " . HLoc::l($e->getMessage()));
                    }
                ?>
                <div class="clearfix"></div>
                <hr />
                <?php
                    $generated_new_time = explode(' ', microtime());
                    $generated_new_time = $generated_new_time[1] + $generated_new_time[0];
                    $g_time = round(($generated_new_time - $generated_time), 4);
                ?>
                <p><small><em><?= HLoc::l("Page generated in") ?> <strong><?= $g_time ?>s</strong> </em></small></p>
            </div>
          </div>
        </div>
        <?php Bundle\Events::Execute("AdminFooter") ?>  
        <script src="admin/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="admin/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
