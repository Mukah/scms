<?php session_start() ?>
<?php require_once("config.php") ?>
<!DOCTYPE html>
<html>
<head>
	<title>sCMS - Posts &raquo; Todas as postagens</title>
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

		<?php $post = mysql_query("SELECT posts.*, users.name as author_name FROM posts JOIN users ON posts.author_id = users.user_id WHERE post_id = '" . $_GET['pid'] . "'") ?>
		<?php $id = mysql_result($post, 0, "post_id") ?>
		<?php $title = mysql_result($post, 0, "title") ?>
		<?php $excerpt = mysql_result($post, 0, "excerpt") ?>
		<?php $content = mysql_result($post, 0, "content") ?>
		<?php $status = mysql_result($post, 0, "status") ?>
		<?php $created_at = mysql_result($post, 0, "created_at") ?>
		<?php $updated_at = mysql_result($post, 0, "updated_at") ?>
		<?php $author = mysql_result($post, 0, "author_name") ?>

		<input type="hidden" id="id" class="form-control input-lg" value="<?php echo $_GET['pid'] ?>">

		<div class="row">
			<div class="col-xs-12 col-md-8">
				<h2><?php echo $title ?></h2>

				<p><?php echo $excerpt ?></p>

				<hr>

				<p><?php echo $content ?></p>
			</div>
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Informações</div>
					<div class="panel-body">
						<p>Status: <span class="label label-warning">Rascunho</span></p>
						<p>Autor: <?php echo $author ?></p>
						<p>Criado em: <?php echo date("d/m/Y \à\s h:i\h", strtotime($created_at)); ?></p>
						<p>
							<a href="posts_edit.php?pid=<?php echo $id ?>" class="btn btn-primary pull-left">Editar</a>
						</p>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">Categorias</div>
					<div class="panel-body">
						
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">Tags</div>
					<div class="panel-body">
						
					</div>
				</div>
			</div>
		</div>
		
	</div>
</body>
</html>