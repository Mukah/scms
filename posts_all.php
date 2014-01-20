<?php session_start() ?>
<?php require_once("config.php") ?>
<!DOCTYPE html>
<html>
<head>
	<title>sCMS - <?php echo $t->__('menu_posts') ?> &raquo; <?php echo $t->__('posts_all_title') ?></title>
	<?php include('headings.php') ?>

	<script type="text/javascript" src="js/ajax_posts.js"></script>
	<script type="text/javascript">
		var actual_page = 1;

		function refresh_posts() {
			list_posts(actual_page, 20,
			function(data){
				$('#posts').html('');
				$.each(data['posts'], function(index, post){
					var status = "";
					switch(post.status){
						case '0':
							status = "[<?php echo $t->__('posts_all_draft') ?>]";
							break;
					}

					$('#posts').append('<tr>\
						<th><input type="checkbox"></th>\
						<th>'+ post.title +'\
						<span class="status-label">'+ status +'</span>\
						<br />\
						<a href="posts_edit.php?pid='+ post.post_id +'"><?php echo $t->__('posts_all_edit') ?></a> | \
						<a class="delete-post" data-id="'+ post.post_id +'" data-confirmation-text="<?php echo $t->__('posts_all_are_you_sure') ?>" data-confirmation="" style="color: #C7254E; cursor: pointer;" role="button"><?php echo $t->__('posts_all_exclude') ?></a> | \
						<a href="posts_preview.php?pid='+ post.post_id +'"><?php echo $t->__('posts_all_view') ?></a></th>\
						<th>'+ post.author_name +'</th>\
						<th>-</th>\
						<th>-</th>\
						<th>'+ post.created_at +'</th>\
					</tr>');

					$('.pagination').html('');
					for (var i = 1; i <= data['page_count']; i++) {
						if(i == actual_page) {
							$('.pagination').append('<li class="active"><a href="#" data-id="'+ i +'">'+ i +'</a></li>');
						} else {
							$('.pagination').append('<li><a href="#" data-id="'+ i +'">'+ i +'</a></li>');
						}
					};
				});
			},
			function(data){
				alert("erro loco");
			});
		}

		$(document).ready(function(){
			refresh_posts();

			$(document).on('click', '.delete-post', function(){
				if($(this).data('confirmation') == 'true') {
					var r = delete_posts(
						[$(this).data('id')],
						// SUCCESS
						function(){
							refresh_posts();
						},
						// FAIL
						function(){
							alert("ERROR BOLADO");
						}
					);
				} else {
					var old_text = $(this).html();
					$(this).fadeOut(function(){
						$(this).html($(this).data('confirmation-text'))
						.data('confirmation', 'true');
					}).fadeIn(function(){
						$(this).delay(3000).fadeOut(function(){
							$(this).html(old_text)
							.data('confirmation', '');
						}).fadeIn();
					});
				}
			});

			$(document).on('click', '.pagination a', function(){
				actual_page = $(this).data('id');
				refresh_posts();
			});
		});
	</script>
</head>
<body>
	<?php include("header.php") ?>
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="home.php"><?php echo $t->__('menu_home') ?></a></li>
			<li class="active"><?php echo $t->__('menu_posts') ?></li>
		</ol>

		<h3><span class="glyphicon glyphicon-pushpin"></span> <?php echo $t->__('posts_all_title') ?> <a href="posts_new.php" class="btn btn-default btn-sm" role="button" style="margin-top: -5px;"><?php echo $t->__('posts_all_new_post') ?></a></h3>

		<hr>
		<ul class="pagination pagination-sm"></ul>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th width="5%"><input type="checkbox"></th>
					<th width="35%"><?php echo $t->__('posts_all_table_title') ?></th>
					<th width="15%"><?php echo $t->__('posts_all_table_author') ?></th>
					<th width="15%"><?php echo $t->__('posts_all_table_categories') ?></th>
					<th width="15%"><?php echo $t->__('posts_all_table_tags') ?></th>
					<th width="15%"><?php echo $t->__('posts_all_table_created_at') ?></th>
				</tr>
			</thead>
			<tbody id="posts">
			<!--
				<?php $posts = mysql_query("SELECT posts.*, users.name as author_name FROM posts JOIN users ON posts.author_id = users.user_id ORDER BY created_at DESC;") ?>
				<?php while($post = mysql_fetch_assoc($posts)): ?>
					<tr>
						<th><input type="checkbox"></th>
						<th><?php echo empty($post["title"]) ? "<i>Sem título</i>" : $post["title"] ?><br />
						<a href="posts_edit.php?pid=<?php echo $post["post_id"] ?>">Editar</a> | 
						<a href="posts_delete.php?pid=<?php echo $post["post_id"] ?>" style="color: #C7254E">Excluir</a> | 
						<a href="posts_preview.php?pid=<?php echo $post["post_id"] ?>">Visualizar</a></th>
						<th><?php echo $post["author_name"] ?></th>
						<th>-</th>
						<th>-</th>
						<th><?php echo date("d/m/Y \à\s h:i\h", strtotime($post["created_at"])); ?></th>
					</tr>
				<?php endwhile; ?>
			-->
			</tbody>
		</table>
		<ul class="pagination pagination-sm"></ul>
		<hr>
	</div>
</body>
</html>