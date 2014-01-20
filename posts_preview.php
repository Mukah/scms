<?php session_start() ?>
<?php require_once("config.php") ?>
<!DOCTYPE html>
<html>
<head>
	<title>sCMS - Posts &raquo; Visualizar postagem</title>
	<?php include('headings.php') ?>

	<script src="js/bootstrap-wysihtml.js"></script>
	<script src="js/wysihtml5-0.3.0.min.js"></script>
	<script src="js/jquery.hotkeys.js"></script>

	<script type="text/javascript" src="js/ajax_posts.js"></script>
</head>
<body>
	<?php include("header.php") ?>
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="home.php">Home</a></li>
			<li><a href="posts_all.php">Postagens</a></li>
			<li class="active">Editar</li>
		</ol>

		<h3><span class="glyphicon glyphicon-pushpin"></span> Visualizar postagem</h3>
		<hr>

		<?php $post = Post::find_by_id($_GET['pid']); ?>

		<input type="hidden" id="id" class="form-control input-lg" value="<?php echo $_GET['pid'] ?>">

		<div class="row">
			<div class="col-xs-12 col-md-8">
				<h2><?php echo $post->get('title') ?></h2>

				<p><?php echo $post->get('excerpt') ?></p>

				<hr>

				<p><?php echo $post->get('content') ?></p>
			</div>
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Informações</div>
					<div class="panel-body">
						<p>Status: <span class="label label-warning">Rascunho</span></p>
						<p>Autor: <?php echo $post->get('author')->get('name') ?></p>
						<p>Criado em: <?php echo date("d/m/Y \à\s h:i\h", strtotime($post->get('created_at'))); ?></p>
						<p>
							<a href="posts_edit.php?pid=<?php echo $post->id() ?>" class="btn btn-primary pull-left">Editar</a>
						</p>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">Categorias</div>
					<div class="panel-body">
						<?php
							if(count($post->get('categories')) > 0) {
								foreach ($post->get('categories') as $key => $category) {
									$category = $category->get('category');
									echo $category->get('name');
								}
							} else {
								?>
								Nenhuma categoria.
								<?php
							}
						?>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">Tags</div>
					<div class="panel-body">
						<?php
							if(count($post->get('tags')) > 0) {
								foreach ($post->get('tags') as $key => $tag) {
									$tag = $tag->get('tag');
									echo $tag->get('name');
								}
							} else {
								?>
								Nenhuma tag.
								<?php
							}
						?>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</body>
</html>