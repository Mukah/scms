<?php
require_once('class.database.php');
Database::connect();

class DBObject {
	// VARS
	private $child;
	private $vars;

	private $attrs = array();

	// CONSTRUCTOR
	function __construct() {
		$this->child = get_class($this);
		$this->vars = get_class_vars($this->child);
	}

	// STATIC METHODS
	protected static function find_by_id($class, $value) {
		$vars = get_class_vars($class);
		$query = mysql_query("SELECT * FROM {$vars['plural']} WHERE {$vars['singular']}_id = {$value};", Database::$connection);
		$row = mysql_fetch_assoc($query);
		$obj = new $class;
		$obj->set_attributes($row);
		return $obj;
	}
	protected static function all($class) {
		$vars = get_class_vars($class);
		$query = mysql_query("SELECT * FROM {$vars['plural']};", Database::$connection);

		$objs = array();
		while($row = mysql_fetch_assoc($query)) {
			$obj = new $class;
			$obj->set_attributes($row);
			array_push($objs, $obj);
		}
		return $objs;
	}
	protected static function where($class, $conditions) {
		if(is_array($conditions)){
			$string = implode(' AND ', $conditions);
		} else {
			$string = $conditions;
		}

		$vars = get_class_vars($class);
		$query = mysql_query("SELECT * FROM {$vars['plural']} WHERE {$string};", Database::$connection);

		$objs = array();
		while($row = mysql_fetch_assoc($query)) {
			$obj = new $class;
			$obj->set_attributes($row);
			array_push($objs, $obj);
		}
		return $objs;
	}

	// INSTANCE METHODS
	public function attrs() {
		return array_keys($this->attrs);
	}
	public function get($field) {
		return $this->attrs[$field];
	}
	public function set($field, $value) {
		return $this->attrs[$field] = $value;
	}
	public function set_attributes($attributes) {
		foreach($attributes as $field => $value) {
			$this->set($field, $value);
		}
	}

	public function save() {
		$values = array();
		foreach ($this->attrs as $field => $value) {
			array_push($values, "{$field} = '{$value}'");
		}
		$string = implode(', ', $values);
		return mysql_query("UPDATE {$this->plural()} SET {$string} WHERE {$this->fk()} = {$this->id()}", Database::$connection);
	}
	public function set_and_save($field, $value) {
		$this->attrs[$field] = $value;
		return save();
	}

	// ENCAPSULATION
	public function id() {
		return $this->attrs["{$this->singular()}_id"];
	}
	public function singular() {
		return $this->vars['singular'];
	}
	public function plural() {
		return $this->vars['plural'];
	}
	public function fk() {
		return "{$this->singular()}_id";
	}
}
class Post extends DBObject {
	// VARS
	public static $plural = "posts";
	public static $singular = "post";


	// CONSTRUCTOR METHODS
	function __construct() {
		// INITIALIZE DBOBJECT
		parent::__construct();
	}

	// IMPLEMENTED METHODS
	public static function find_by_id($value) { return parent::find_by_id(get_class(), $value); }
	public static function all() { return parent::all(get_class()); }
	public static function where($conditions) {
		return parent::where(get_class(), $conditions);
	}
}


var_dump(Post::where('post_id = 1'));
//$post = Post::find_by_id(3);
//$posts = Post::all();

//foreach($posts as $post){
//echo $post->get('title') . "<br>";
//$post->set('title', 'Como cuidar de um macaco simpatico 2');
//$post->set_attributes(array('title' => 'Vai menina senta na pica'));
//echo $post->get('title');
//var_dump($post->save());
//}
?>