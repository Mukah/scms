<?php
require_once('classes/class.database.php');
require_once('classes/class.translation.php');

Database::connect();

$t = new Translator("pt-BR");
?>