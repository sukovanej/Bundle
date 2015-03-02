<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="<?= $Page->ThemeRoot ?>/reset.css" />
	<link rel="stylesheet" href="<?= $Page->ThemeRoot ?>/style.css?get=get" />
	<link rel="icon" type="image/png" href="<?= $Page->Icon ?>?new=ico" />
	<title><?= $Page->Name ?> | <?= $Page->Actual ?></title>
</head>
<body>
	<div id="wrapper">
		<div id="page">		
			<div id="header">
				<div id="logo">
					<h1><a href="./"><?= $Page->Name ?></a></h1>
				</div>
			</div>
			<div id="content">
				<div id="panel">
					<!-- Navigace -->
					<div class="panel_title">Navigace</div>
					<div class="panel_content">
						<ul>
							<?php foreach($Page->Menu as $Menu): ?>
								<li <?= $Menu->Current ?>><a href="<?= $Menu->Url ?>"><?= $Menu->Title ?></a></li>
								<?php if (count($Menu->Children) > 0): ?>
									<ul class="sub-menu">
									<?php foreach($Menu->Children as $MenuChild): ?>
										<li <?= $MenuChild->Current ?>><a href="<?= $MenuChild->Url ?>"><?= $MenuChild->Title ?></a></li>
									<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php $Page->Panel() ?>
				</div>
				<div id="text">
					<!-- Obsah -->
					<?php $Page->Content() ?>
				</div>
			</div>
		</div>	
		<div id="clear"></div>	
		<footer>
			<content>
				<?php $Page->Footer() ?>
				<p>Powered by <a href="http://bundle-cms.cz/" target="_blank">Bundle</a>, <a href="./administrace">Administrace</a></p>
			</content>
		</footer>		
	</div>			

</body>
</html>
