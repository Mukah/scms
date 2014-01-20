<?php
require_once("class.dbobject.php");

class User extends DBObject {
	public static $plural = "users";
	public static $singular = "user";

	function __construct() {
		parent::__construct();
	}
}

?>