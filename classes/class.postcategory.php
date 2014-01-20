<?php
require_once("class.dbobject.php");

class PostCategory extends DBObject {
	public static $plural = "post_categories";
	public static $singular = "post_category";

	function __construct() {
		parent::__construct();
		parent::__belongs_to("Post", "post_id", "post");
		parent::__belongs_to("Category", "category_id", "category");
	}
}

?>