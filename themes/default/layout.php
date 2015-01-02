<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="<?= $Page->ThemeRoot ?>/style_default.css" />
	<link rel="stylesheet" href="<?= $Page->ThemeRoot ?>/style_<?= HConfiguration::Get("DefaultThemeStyleType") ?>.css?get=get" />
	<link rel="stylesheet" href="<?= $Page->ThemeRoot ?>/reset.css" />
	<link rel="icon" type="image/png" href="<?= $Page->Icon ?>?new=ico" />
	<title><?= $Page->Name ?> | <?= $Page->Actual ?></title>
</head>
<body>
	<div id="wrapper">
		<div id="page">		
		<div id="header">
			<div id="menu">
				<content>
					<!-- Navigace -->
					<ul id="parent">
					<?php foreach($Page->Menu as $Menu): ?>
						<li><a href="<?= $Menu->Url ?>" <?= $Menu->Current ?>><?= $Menu->Title ?></a>
							<?php if (count($Menu->Children) > 0): ?>
							<ul class="sub-menu">
							<?php foreach($Menu->Children as $MenuChild): ?>
								<li><a href="<?= $MenuChild->Url ?>"><?= $MenuChild->Title ?></a></li>
							<?php endforeach; ?>
							</ul>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
					</ul>
				</content>
			</div>
			<div id="logo">
				<h1><?= $Page->Name ?></h1>
			</div>
		</div>			
			<div id="full_content">
				<?php $Page->Header(); ?>
				<?php $Page->Content("full_content"); ?>
			</div>
			<div id="content">
				<div id="panel">
					<?php $Page->Panel() ?>
				</div>
				<div id="text">
					<!-- Obsah -->
					<?php $Page->Content() ?>
				</div>
			</div>	

		</div>		
		<footer>
			<content>
				<?php $Page->Footer() ?>
				<p>Powered by <a href="http://bundle-cms.cz/" target="_blank">Bundle</a>, <a href="./administrace">Administrace</a></p>
			</content>
		</footer>		
	</div>			

</body>
</html>
