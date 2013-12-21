function update_post(post, success, fail){
	$.post(
		"ajax/posts_update.php",
		{
			id: $('#id').val(),
			title: $('#title').val(),
			content: $('#editor').cleanHtml(),
			excerpt: $('#excerpt').val(),
			status: post.data.status,
		}
	).done(post.data.success)
	.fail(post.data.fail);
}

function list_posts(actual_page, per_page, success, fail){
	$.ajax({
		url: "ajax/posts_list.php",
		dataType: "json",
		type: "POST",
		data: {
			page: actual_page,
			show: per_page
		}
	}).done(success)
	.fail(fail);
}

function delete_posts(post_ids, success, fail) {
	$.post("ajax/posts_delete.php", {
		ids: post_ids
	}).done(function(data){
		if(data == "true") {
			success();
		} else {
			fail();
		}
	});
}