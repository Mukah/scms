<?php
require_once("class.dbobject.php");

class PostTag extends DBObject {
	public static $plural = "post_tags";
	public static $singular = "post_tag";

	function __construct() {
		parent::__construct();
		parent::__belongs_to("Post", "post_id", "post");
		parent::__belongs_to("Tag", "tag_id", "tag");
	}
}

?>