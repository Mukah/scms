<?php
session_start();
require_once("../config.php");
$id = $_POST["id"];
$title = $_POST["title"];
$content = $_POST["content"];
$excerpt = $_POST["excerpt"];
$status = $_POST["status"];

if(mysql_query("UPDATE posts SET title = '{$title}', content = '{$content}', excerpt = '{$excerpt}', status = '{$status}', updated_at = NOW() WHERE post_id = {$id}")) {
	echo json_encode(true);
} else {
	echo json_encode(false);
}
?>