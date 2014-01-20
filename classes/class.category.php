<?php
require_once("class.dbobject.php");

class Category extends DBObject {
	public static $plural = "categories";
	public static $singular = "category";

	function __construct() {
		parent::__construct();
	}
}

?>