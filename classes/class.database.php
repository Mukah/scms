<?php
class Database {
	public static $connection;
	public static function connect() {
		Database::$connection = mysql_connect("localhost", "root", "");
		mysql_select_db("scms", Database::$connection);
	}
}
?>