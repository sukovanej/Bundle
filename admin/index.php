<?php $generated_time = explode(' ', microtime()); // Vykreslovací čas ?>
<?php $generated_time = $generated_time[1] + $generated_time[0]; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Administrační sekce webu</title>
        <?php header("X-Frame-Options: Deny"); ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<link rel="stylesheet" href="./admin/reset.css" />
        <link media="only screen and (min-device-width: 500px)" rel="stylesheet" href="./admin/style.css" />
		<link media="only screen and (max-device-width: 500px)" href="./admin/mobile-style.css" 
			type="text/css" rel="stylesheet" />
        <link rel="icon" type="image/png" href="./images/icon.png?get=ico" />
        <script type="text/javascript" src="./admin/jquery.js"></script>
        <script type="text/javascript" src="./admin/js.js"></script>
        <script src="admin/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
        <?php Bundle\Events::Execute("AdminHead") ?>
        <?php 
            require("login.php");
            require("admin.php");
        ?>
    </head>
    <body>
        <div id="dialog-bg"></div>
        <div id="dialog"></div>
        <div id="mobile-menu" onclick="toggle_menu()">
			<a>Zobrazit navigaci &rsaquo; </a>
		</div>
        <div id="head">
            <div id="head-content">
				<a href="http://bundle-cms.cz/" target="_blank"><img src="./images/logo.png" class="logo_" /></a>
                <div id="menu">
					<?php if ($User->Role <= 1): ?>
					<ul>
						<li><a href="administrace-vytvorit-clanek"><img src="./images/plus.png" />Vytvořit článek</a></li>
						<li><a href="./administrace-vytvorit-stranku"><img src="./images/Empty document new.png" />Vytvořit stránku</a></li>
						<?php Bundle\Events::Execute("AdminLeftTopNav") ?>	
					</ul>
					<?php endif; ?>
					<ul class="right_top">
						<?php Bundle\Events::Execute("AdminRightTopNav") ?>
						<li><a href="./" target="_blank"><img src="./images/home.png" />Navštívit web</a></li>
						<li ><a href="./administrace-profil" title="Upravit profil"><img src="./images/heart.png" />Upravit profil</a></li>
						<li class="no-space"><a href="administrace-odhlasit" title="Odhlásit"><img src="./images/Badge-multiply.png" /></a></li>
						<li><a title="<?= $User->RoleString ?>"><img src="<?= $User->Photo ?>" /><strong><?= $User->Username ?></strong></a></li>
					</ul>
				</div>
            </div>
        </div>
        
        <nav><?php if ($User->Role <= 1): ?>
			<ul>
				<li><a href="administrace" title="Přehled systému"><img src="./images/home.png" />Přehled</a></li>				
				
				<?php if ($User->Role <= 1): ?>
				
				<li><a href="administrace-clanky"><img src="./images/Hard drive.png" />Články</a></li>
				<li><a href="administrace-komentare"><img src="./images/Bubble chat.png" />Komentáře</a></li>	
				<li><a href="./administrace-stranky"><img src="./images/Cabinet.png" />Stránky</a></li> 
				
				<?php endif; if ($User->Role == 0): ?>
				
				<li><a href="./administrace-uzivatele"><img src="./images/User.png" />Uživatelé</a></li> 
				<li><a href="./administrace-kategorie"><img src="./images/bookmark.png" />Kategorie</a></</li> 
				<li><a href="./administrace-baliky"><img src="./images/Plugins.png" />Balíky</a></li> 
				<li><a href="administrace-nastaveni"><img src="./images/blueprint4.png" />Nastavení</a></li>
				
				<?php if(file_exists($Page->ThemeRoot . "/admin.php")): ?> 
				<li><a href="administrace-nastaveni-sablony"><img src="./images/theme_config.png" />Nastavení šablony</a></li>
				<?php endif; ?>
				
				<li><a href="administrace-vzhled"><img src="./images/Console.png" />Generování obsahu</a></li>
				<li><a href="administrace-menu"><img src="./images/list.png" />Menu</a></li>
				
				<?php endif; ?>
			</ul>
			<div id="clear-nav"></div>
			<ul>
				<?php if ($User->Role == 0): ?>
					<?php foreach(Bundle\Packages::get_packages_admin_menu() as $package): ?>
					<li><a href="./administrace-spravovat-balik-<?= $package->Name ?>" title="<?= $package->Name ?>">
						<img src="<?= $package->IconUrl ?>" /><?= $package->Config->name ?></a></li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul><?php endif; ?>
			
			<ul>
				<?php Bundle\Events::Execute("AdminNav") ?>	
			</ul>
        </nav>
        
        <?php if ($User->Role == 0): ?>	
		<?php endif; ?>
        <div id="content">
            <?php
				/* 
				 * 0 = administrátor
				 * 1 = redaktor
				 * 2 = uživatel 
				 */
				 
				$pages = array(
					"nastaveni" => "configpage;0",
					"profil" => "profil;2",
					"menu" => "menu;0",
					"vzhled" => "configpage_theme;0",
					"uzivatele" => "users;0",
					"vytvorit-stranku" => "create_page;0",
					"stranky" => "pages;0",
					"upravit-stranku;s" => "edit_page;0",
					"kategorie" => "categories;0",
					"vytvorit-clanek" => "create_article;1",
					"clanky" => "articles;1",
					"upravit-clanek;s" => "edit_article;1",
					"komentare" => "comments;0",
					"baliky" => "packages;0",
					"spravovat-balik;s" => "package;2",
					"instalovat-balik;s" => "install_package;0",
					"odinstalovat-balik;s" => "uninstall_package;0",
					"nastaveni-sablony" => "theme_admin;0"
				);
				
				/* 
				 * Vykreslování obsahu administrace, NEUPRAVOVAT!
				 */
				
				$bool_show = false;
				foreach($pages as $page => $file) {
					$page_c = preg_split("[;]", $page);
					$file_c = preg_split("[;]", $file);
					
					if ($User->Role <= $file_c[1]) {
						if ((count($page_c) == 2 && $page_c[1] == "s" && HString::startsWith($subrouter, $page_c[0])) || ($page_c[0] == $subrouter)) {
							require($file_c[0] . ".php");
							$bool_show = true;
						}
					}
				}
				
				if (!$bool_show)
					require("info.php");
            ?>
        </div>
        <?php
			$generated_new_time = explode(' ', microtime());
			$generated_new_time = $generated_new_time[1] + $generated_new_time[0];
			$g_time = round(($generated_new_time - $generated_time), 4);
		?>
		<footer>
			<p>Systém <strong>Bundle <?= $Website->Bundle ?></strong> | Stránka vygenerována za <strong><?= $g_time ?>s</strong> 
			<?php Bundle\Events::Execute("AdminFooter") ?>	
				
			</p>
		</footer>
    </body>
</html>
