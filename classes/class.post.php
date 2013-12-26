<?php
require_once('class.database.php');
Database::connect();

class DBObject {
	protected $obj;
	protected $table_name;
	protected $singular;

	private $attrs = array();

	function __construct($obj, $table_name, $singular) {
		$this->obj = $obj;
		$this->table_name = $table_name;
		$this->singular = $singular;
	}

	public function all() {
		return "SELECT * FROM {$this->table_name};";
	}
	public function find_by_id($value) {
		$query = mysql_query("SELECT * FROM {$this->table_name} WHERE {$this->singular}_id = {$value};", Database::$connection);
		$d = mysql_fetch_assoc($query);

		foreach ($d as $key => $value) {
			$this->obj->set($key, $value);
		}
		return $this->obj;
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
		$query = mysql_query("UPDATE {$this->table_name} SET {$string} WHERE {$this->singular}_id = {$this->id()}");
	}
	public function id() {
		return $this->attrs["{$this->singular}_id"];
	}
}
class Post extends DBObject {
	function __construct() {
		parent::__construct($this, "posts", "post");
	}
}

$post = new Post();
$post->find_by_id(1);
echo $post->get('title');
$post->set('title', 'Como cuidar de um macaco simpatico');
echo $post->get('title');
$post->save();
?>