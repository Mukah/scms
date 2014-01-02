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
	<script type="text/javascript">
		$(document).ready(function(){
			$('#editor').wysiwyg();
			$('#post-publish').bind('click',
				{ 
					status: 1,
					success: function(data){
						if(data == "true"){
							show_alert($('#alert-success-1'), 2000);
						} else {
							show_alert($('#alert-error-1'), 2000);
						}
					},
					fail: function(data){ show_alert($('#alert-error-2'), 2000); }
				}, update_post);
			$('#post-save').bind('click',
				{ 
					status: 0,
					success: function(data){
						if(data == "true"){
							show_alert($('#alert-success-2'), 2000);
						} else {
							show_alert($('#alert-error-1'), 2000);
						}
					},
					fail: function(data){ show_alert($('#alert-error-2'), 2000); }
				}, update_post);

			$('#delete-post').click(function(){
				if($(this).data('confirmation') == 'true') {
					var r = delete_posts([$(this).data('id')],
						// SUCCESS
						function(){
							show_alert($('#alert-success-3'), 2000, function(){
								window.location = './posts_all.php';
							});
						},
						// FAIL
						function(){
							show_alert($('#alert-error-3'), 2000);
						}
					);
				} else {
					confirm_button($(this));
				}
			});
		});
	</script>
</head>
<body>
	<?php include("header.php") ?>
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="home.php">Home</a></li>
			<li><a href="posts_all.php">Postagens</a></li>
			<li class="active">Editar</li>
		</ol>

		<h3><span class="glyphicon glyphicon-pushpin"></span> Editar postagem</h3>
		<hr>

		<div id="alert-success-1" class="alert alert-success hidden">
			Postagem publicada com sucesso. <a href="posts_show.php?pid=<?php echo $_GET['pid'] ?>" target="_blank" class="alert-link">Visualizar</a>.
		</div>
		<div id="alert-success-2" class="alert alert-success hidden">
			Postagem salva com sucesso. <a href="posts_show.php?pid=<?php echo $_GET['pid'] ?>" target="_blank" class="alert-link">Visualizar</a>.
		</div>
		<div id="alert-success-3" class="alert alert-success hidden">
			Postagem excluida com sucesso.
		</div>

		<div id="alert-error-1" class="alert alert-danger hidden">
			Erro ao tentar salvar a postagem. Confira os dados preenchidos.
		</div>
		<div id="alert-error-2" class="alert alert-danger hidden">
			Não foi possível salvar a postagem. Tente novamente.
		</div>
		<div id="alert-error-3" class="alert alert-danger hidden">
			Não foi possível excluir a postagem. Tente novamente.
		</div>

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
				<p>
					<input type="text" id="title" placeholder="Título" class="form-control input-lg" value="<?php echo $title ?>">
				</p>

				<p>
					<div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
						<div class="btn-group">
							<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Font"><i class="glyphicon glyphicon-font"></i><b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a data-edit="fontName Serif" style="font-family:'Serif'">Serif</a></li>
								<li><a data-edit="fontName Sans" style="font-family:'Sans'">Sans</a></li>
								<li><a data-edit="fontName Arial" style="font-family:'Arial'">Arial</a></li>
								<li><a data-edit="fontName Arial Black" style="font-family:'Arial Black'">Arial Black</a></li>
								<li><a data-edit="fontName Courier" style="font-family:'Courier'">Courier</a></li>
								<li><a data-edit="fontName Courier New" style="font-family:'Courier New'">Courier New</a></li>
								<li><a data-edit="fontName Comic Sans MS" style="font-family:'Comic Sans MS'">Comic Sans MS</a></li>
								<li><a data-edit="fontName Helvetica" style="font-family:'Helvetica'">Helvetica</a></li>
								<li><a data-edit="fontName Impact" style="font-family:'Impact'">Impact</a></li>
								<li><a data-edit="fontName Lucida Grande" style="font-family:'Lucida Grande'">Lucida Grande</a></li>
								<li><a data-edit="fontName Lucida Sans" style="font-family:'Lucida Sans'">Lucida Sans</a></li>
								<li><a data-edit="fontName Tahoma" style="font-family:'Tahoma'">Tahoma</a></li>
								<li><a data-edit="fontName Times" style="font-family:'Times'">Times</a></li>
								<li><a data-edit="fontName Times New Roman" style="font-family:'Times New Roman'">Times New Roman</a></li>
								<li><a data-edit="fontName Verdana" style="font-family:'Verdana'">Verdana</a></li>
							</ul>
						</div>
						<div class="btn-group">
							<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Font Size"><i class="glyphicon glyphicon-text-height"></i>&nbsp;<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
								<li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
								<li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
							</ul>
						</div>
						<div class="btn-group">
							<a class="btn btn-default btn-sm" data-edit="bold" title="" data-original-title="Bold (Ctrl/Cmd+B)"><i class="glyphicon glyphicon-bold"></i></a>
							<a class="btn btn-default btn-sm" data-edit="italic" title="" data-original-title="Italic (Ctrl/Cmd+I)"><i class="glyphicon glyphicon-italic"></i></a>
							<a class="btn btn-default btn-sm" data-edit="underline" title="" data-original-title="Underline (Ctrl/Cmd+U)"><i class="glyphicon glyphicon-text-width"></i></a>
						</div>
						<div class="btn-group">
							<a class="btn btn-default btn-sm" data-edit="insertunorderedlist" title="" data-original-title="Bullet list"><i class="glyphicon glyphicon-list"></i></a>
							<a class="btn btn-default btn-sm" data-edit="insertorderedlist" title="" data-original-title="Number list"><i class="glyphicon glyphicon-list-alt"></i></a>
							<a class="btn btn-default btn-sm" data-edit="outdent" title="" data-original-title="Reduce indent (Shift+Tab)"><i class="glyphicon glyphicon-indent-left"></i></a>
							<a class="btn btn-default btn-sm" data-edit="indent" title="" data-original-title="Indent (Tab)"><i class="glyphicon glyphicon-indent-right"></i></a>
						</div>
						<div class="btn-group">
							<a class="btn btn-default btn-sm" data-edit="justifyleft" title="" data-original-title="Align Left (Ctrl/Cmd+L)"><i class="glyphicon glyphicon-align-left"></i></a>
							<a class="btn btn-default btn-sm" data-edit="justifycenter" title="" data-original-title="Center (Ctrl/Cmd+E)"><i class="glyphicon glyphicon-align-center"></i></a>
							<a class="btn btn-default btn-sm" data-edit="justifyright" title="" data-original-title="Align Right (Ctrl/Cmd+R)"><i class="glyphicon glyphicon-align-right"></i></a>
							<a class="btn btn-default btn-sm" data-edit="justifyfull" title="" data-original-title="Justify (Ctrl/Cmd+J)"><i class="glyphicon glyphicon-align-justify"></i></a>
						</div>
						<div class="btn-group">
							<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Hyperlink"><i class="glyphicon glyphicon-link"></i></a>
							<div class="dropdown-menu input-append">
								<input class="span2" placeholder="URL" type="text" data-edit="createLink">
								<button class="btn" type="button">Add</button>
							</div>
							<a class="btn btn-default btn-sm" data-edit="unlink" title="" data-original-title="Remove Hyperlink"><i class="glyphicon glyphicon-remove"></i></a>

						</div>

						<div class="btn-group">
							<a class="btn btn-default btn-sm" title="" id="pictureBtn" data-original-title="Insert picture (or just drag &amp; drop)"><i class="glyphicon glyphicon-picture"></i></a>
							<input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" style="opacity: 0; position: absolute; top: 0px; left: 0px; width: 37px; height: 30px;">
						</div>
						<div class="btn-group">
							<a class="btn btn-default btn-sm" data-edit="undo" title="" data-original-title="Undo (Ctrl/Cmd+Z)"><i class="glyphicon glyphicon-backward"></i></a>
							<a class="btn btn-default btn-sm" data-edit="redo" title="" data-original-title="Redo (Ctrl/Cmd+Y)"><i class="glyphicon glyphicon-forward"></i></a>
						</div>
					</div>
				</p>

				<p>
					<div id="editor" class="form-control" contenteditable="true" style="min-height: 300px; font-family: arial; overflow-y: scroll;"><?php echo $content ?></div>
				</p>

				<hr>

				<p>
					<textarea id="excerpt" placeholder="Resumo" rows="3" maxlength="511" class="form-control"><?php echo $excerpt ?></textarea>
				</p>
			</div>
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Publicar</div>
					<div class="panel-body">
						<p>Status: <span class="label label-warning">Rascunho</span></p>
						<p>Autor: <?php echo $author ?></p>
						<p>Criado em: <?php echo date("d/m/Y \à\s h:i\h", strtotime($created_at)); ?></p>
						<p>
							<a id="delete-post" class="btn btn-link pull-left" data-id="<?php echo $id ?>" data-confirmation-text="Tem certeza?" data-confirmation="">Excluir</a>
							<input id="post-publish" type="button" value="Publicar" class="btn btn-primary pull-right" style="margin-left: 10px;">
							<input id="post-save" type="button" value="Salvar rascunho" class="btn btn-default pull-right">
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