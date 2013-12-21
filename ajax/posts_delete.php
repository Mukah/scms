<?php
session_start();
require_once("../config.php");
$ids = $_POST["ids"];

if(isset($ids) and !empty($ids)) {
	foreach ($ids as $id) {
		mysql_query("UPDATE posts SET deleted_at = NOW() WHERE post_id = {$id}");	
	}
	echo json_encode(true);
} else {
	echo json_encode(false);
}
?>