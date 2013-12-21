<?php
$mysql = mysql_connect("localhost", "root", "");
mysql_select_db("scms");

require_once('classes/class.translation.php');
$t = new Translator("pt-BR");
?>