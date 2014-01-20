<?php
require_once("class.dbobject.php");

class Tag extends DBObject {
	public static $plural = "tags";
	public static $singular = "tag";

	function __construct() {
		parent::__construct();
	}
}

?>