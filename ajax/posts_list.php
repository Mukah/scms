<?php
session_start();
require_once("../config.php");

$page = isset($_POST["page"]) ? $_POST["page"] - 1 : 0;
$show = isset($_POST["show"]) ? $_POST["show"] : 2;

$posts = mysql_query("SELECT SQL_CALC_FOUND_ROWS posts.*, users.name as author_name FROM posts JOIN users ON posts.author = users.user_id WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT ". $page * $show .", {$show}");
$posts_count = mysql_result(mysql_query("SELECT FOUND_ROWS() as count"), 0, "count");

$arr_posts = array();
$arr_posts["showing"] = $show;
$arr_posts["page"] = $page;
$arr_posts["page_count"] = ceil($posts_count/$show);
$arr_posts["count"] = $posts_count;
$arr_posts["posts"] = array();

while($post = mysql_fetch_assoc($posts)){
	$post["created_at"] = date("d/m/Y \à\s h:i\h", strtotime($post["created_at"]));
	array_push($arr_posts["posts"], $post);
}

echo json_encode($arr_posts);
?>