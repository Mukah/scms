<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="navbar-header">
		<a class="navbar-brand" href="home.php">sCMS</a>
		<ul class="nav navbar-nav navbar-left">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $t->__('menu_posts') ?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="posts_all.php"><?php echo $t->__('menu_posts') ?></a></li>
					<li><a href="posts_new.php"><?php echo $t->__('menu_new_post') ?></a></li>
					<li><a href="#"><?php echo $t->__('menu_categories') ?></a></li>
					<li><a href="#"><?php echo $t->__('menu_tags') ?></a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Media <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="#">Biblioteca</a></li>
					<li><a href="#">Nova media</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuários <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="#">Todos os usuários</a></li>
					<li><a href="#">Novo usuário</a></li>
				</ul>
			</li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="#">Conta</a></li>
		</ul>
	</div>
</nav>