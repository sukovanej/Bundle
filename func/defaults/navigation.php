<nav class="navbar navbar-<?= $type ?>">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
				<span class="sr-only"><?= HLoc::l("Toggle navigation") ?></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="./"><?= $Page->Name ?></a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
			<ul class="nav navbar-nav">
				<?php foreach($Page->Menu as $Menu): ?>
					<li class="<?= $Menu->Current ?>"><a href="<?= $Menu->Url ?>"
						<?php if ($Menu->HasChildren): ?>
						class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"
						<?php endif; ?>
						><?= $Menu->Title ?> 
						<?php if ($Menu->HasChildren): ?><span class="caret"></span><?php endif; ?>
						</a>

						<?php if ($Menu->HasChildren): ?>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= $Menu->Url ?>"><span class="glyphicon glyphicon-share-alt"></span> <?= $Menu->Title ?> </a></li>
							<?php foreach($Menu->Children as $MenuChild): ?>
							<li><a href="<?= $MenuChild->Url ?>"><?= $MenuChild->Title ?></a></li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>

					</li>
				<?php endforeach; ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if (Bundle\User::IsLogged()): ?>
					<li><a href="administration"><span class="btn btn-primary btn-sm pull-right log-reg-btn"><?= $User->Username ?></a></a></li>
				<?php else: ?>
					<li><a href="login"><span class="btn btn-success btn-sm pull-right log-reg-btn"><?= HLoc::l("Log in") ?></a></a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>