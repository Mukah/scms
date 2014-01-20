<?php
require_once('classes/class.database.php');
require_once('classes/class.translation.php');
require_once('classes/class.post.php');
require_once('classes/class.user.php');
require_once('classes/class.category.php');
require_once('classes/class.postcategory.php');
require_once('classes/class.tag.php');
require_once('classes/class.posttag.php');

Database::connect();

$t = new Translator("pt-BR");
?>