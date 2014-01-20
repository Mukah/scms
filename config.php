<?php
require_once('classes/class.database.php');
require_once('classes/class.translation.php');
require_once('classes/class.post.php');
require_once('classes/class.user.php');

Database::connect();

$t = new Translator("pt-BR");
?>