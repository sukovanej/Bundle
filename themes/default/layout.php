<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf8">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="<?= $Page->ThemeRoot ?>/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?= $Page->ThemeRoot ?>/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="<?= $Page->ThemeRoot ?>/css/style.css?get=<?= date("s") ?>">
		<link rel="icon" type="image/png" href="<?= $Page->Icon ?>?get=<?= date("s") ?>" />
		<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="<?= $Page->ThemeRoot ?>/bootstrap.min.js"></script>
		<script type="text/javascript"> $(function () { $('[data-toggle="tooltip"]').tooltip() }) // tooltip bug </script>
		<?php $Page->Head() ?>
		<title><?= $Page->Name ?> | <?= $Page->Actual ?></title>
	</head>
	<body>
		<div id="page">
			<?php $Page->Navigation(); ?>
			<header>
				<?php if($Url->Type == "home" && HConfiguration::get("bootstrap_theme_config_show_jumbotron") != -1): ?>
				<div class="jumbotron">
		        	<h1><?= HConfiguration::get("bootstrap_theme_config_show_jumbotron_title") ?></h1>
		        	<?= HConfiguration::get("bootstrap_theme_config_show_jumbotron_text") ?>
		        	<div class="clearfix"></div>
		      	</div>
		      	<?php endif; ?>
		      	<?php $Page->Header(); ?>
			</header>
			<div id="wrapper">
				<content>
					<div class="col-md-8">
						<?php $Page->Content() ?>
					</div>
					<div class="col-md-4 _panel">
						<?php $Page->Panel() ?>
					</div>
					<div class="clear"></div>
				</content>
				<div class="clearfix"></div><hr />
				<footer>
					<?php $Page->Footer() ?>
					<p>&copy; 2014 - <?= date("Y") ?>. Powered by <a href="http://bundle-cms.cz/">Bundle</a>. <a href="./administration"><?= HLoc::l("Administration") ?></a></p>
				</footer>
			</div>
		</div>
	</body>
</html>