<?php
session_start();
require_once("config.php");
mysql_query("INSERT INTO posts VALUES(null, '', '', '', 0, " . $_SESSION["uid"] . ", NOW(), NOW(), null);");
header('location: posts_edit.php?pid=' . mysql_insert_id());
?>