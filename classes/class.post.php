<?php
require_once('class.database.php');
Database::connect();

class DBObject {
	private $attrs = array();

	function __construct() {
		//$this->obj = $obj;
		//$this->plural = $plural;
		//$this->singular = $singular;
	}

	public function all() {
		return "SELECT * FROM {$this->plural};";
	}
	protected static function find_by_id($obj, $value) {
		get_class($this)
		//$query = mysql_query("SELECT * FROM {$this->plural} WHERE {$this->singular}_id = {$value};", Database::$connection);
		//$d = mysql_fetch_assoc($query);



		//$obj = new Post();
		//foreach ($d as $key => $value) {
		//	$obj->set($key, $value);
		//}
		//return $obj;
	}
	public function get($field) {
		return $this->attrs[$field];
	}
	public function set($field, $value) {
		return $this->attrs[$field] = $value;
	}
	public function attrs() {
		return array_keys($this->attrs);
	}
	public function save() {
		$values = array();
		foreach ($this->attrs as $field => $value) {
			array_push($values, "{$field} = '{$value}'");
		}
		$string = implode(', ', $values);
		return mysql_query("UPDATE {$this->plural} SET {$string} WHERE {$this->singular}_id = {$this->id()}");
	}
	public function id() {
		return $this->attrs["{$this->singular}_id"];
	}
}
class Post extends DBObject {
	protected static $plural = "posts";
	protected static $singular = "post";

	function __construct() {
		parent::__construct();
		$this->plural;
		$this->singular;
	}

	public static function find_by_id($value) {

	}
}

$post = new Post();
$post = $post->find_by_id(1);
echo $post->get('title');
$post->set('title', 'Como cuidar de um macaco simpatico');
echo $post->get('title');
$post->save();
?>