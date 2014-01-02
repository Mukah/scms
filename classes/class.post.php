<?php
require_once('class.database.php');
Database::connect();

abstract class DBObject {
	// VARS
	private $child;
	private $vars;

	private $attrs = array();
	private $fks = array();

	// CONSTRUCTOR
	function __construct() {
		$this->child = get_called_class();
		$this->vars = get_class_vars($this->child);
	}

	// STATIC METHODS
	public static function find_by_id($value) {
		$class = get_called_class();
		$vars = get_class_vars($class);
		$query = Database::query("SELECT * FROM {$vars['plural']} WHERE {$vars['singular']}_id = {$value};");
		if(mysql_num_rows($query) > 0) {
			$row = mysql_fetch_assoc($query);
			$obj = new $class;
			$obj->set_attributes($row);
			return $obj;
		} else {
			return NULL;
		}
	}
	public static function exists($value) {
		$class = get_called_class();
		$vars = get_class_vars($class);
		$query = Database::query("SELECT * FROM {$vars['plural']} WHERE {$vars['singular']}_id = {$value};");
		return ((mysql_num_rows($query) > 0) ? true : false);
	}
	public static function all($options = array()) {
		$class = get_called_class();
		$vars = get_class_vars($class);
		$query = Database::query("SELECT * FROM {$vars['plural']} " . DBObject::options_to_query($options) . ";");
		if(mysql_num_rows($query) > 0) {
			$objs = array();
			while($row = mysql_fetch_assoc($query)) {
				$obj = new $class;
				$obj->set_attributes($row);
				array_push($objs, $obj);
			}
			return $objs;
		} else {
			return NULL;
		}
	}
	public static function where($conditions, $options = array()) {
		if(is_array($conditions)){
			$string = implode(' AND ', $conditions);
		} else {
			$string = $conditions;
		}

		$class = get_called_class();
		$vars = get_class_vars($class);
		$query = Database::query("SELECT * FROM {$vars['plural']} WHERE {$string} " . DBObject::options_to_query($options) . ";");
		if(mysql_num_rows($query) > 0) {
			$objs = array();
			while($row = mysql_fetch_assoc($query)) {
				$obj = new $class;
				$obj->set_attributes($row);
				array_push($objs, $obj);
			}
			return $objs;
		} else {
			return NULL;
		}
	}

	private static function options_to_query($options) {
		$opts = array('group' => '');
		foreach ($options as $attr => $value) {
			switch($attr) {
				case 'order_by': $opts['order_by'] = "ORDER BY {$value}"; break;
				case 'group_by': $opts['group_by'] = "GROUP BY {$value}"; break;
				case 'limit': $opts['limit'] = "LIMIT {$value}"; break;
			}
		}
		$string = "";
		$string .= (empty($opts['group_by'])) ? NULL : $opts['group_by'];
		$string .= (empty($opts['order_by'])) ? NULL : " ".$opts['order_by'];
		$string .= (empty($opts['limit'])) ? NULL : " ".$opts['limit'];

		return $string;
	}

	// INSTANCE METHODS
	public function attrs() {
		return array_keys($this->attrs);
	}
	public function get($field) {
		if(isset($this->fks[$field])) {
			$fk = $this->fks[$field];
			if(isset($this->attrs[$fk['field']])) {
				return $fk['class']::find_by_id($this->attrs[$fk['field']]);
			}
		}
		return (isset($this->attrs[$field]) ? $this->attrs[$field] : NULL);
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
		if(self::exists($this->id())){
			return $this->update();
		} else {
			return $this->insert();
		}
	}
	private function insert() {
		$values = array();
		foreach ($this->attrs as $field => $value) {
			array_push($values, "{$field} = '{$value}'");
		}
		unset($values[$this->pk()]);
		$string = implode(', ', $values);
		$query = Database::query("INSERT INTO {$this->plural()} SET {$string};");
		$this->set($this->pk(), mysql_insert_id(Database::$connection));
		return $query;
	}
	private function update() {
		$values = array();
		foreach (array_filter($this->attrs) as $field => $value) {
			switch(gettype($value)){
				case "integer":	case "double":
					$val = $value;
					break;
				case "NULL":
					$val = "NULL";
					break;
				default: $val = "'{$value}'"; break;
			}
			array_push($values, "{$field} = {$val}");
		}
		unset($values[$this->pk()]);
		$string = implode(', ', $values);
		return Database::query("UPDATE {$this->plural()} SET {$string} WHERE {$this->pk()} = {$this->id()};");
	}
	public function set_and_save($field, $value) {
		$this->attrs[$field] = $value;
		return save();
	}

	// ENCAPSULATION
	public function id() {
		return ((isset($this->attrs[$this->pk()])) ? $this->attrs[$this->pk()] : -1);
	}
	public function singular() {
		return $this->vars['singular'];
	}
	public function plural() {
		return $this->vars['plural'];
	}
	public function pk() {
		return "{$this->singular()}_id";
	}

	protected function __fk($field, $class, $label) {
		$this->fks[$label] = array('class' => $class, 'field' => $field);
	}
}
class Post extends DBObject {
	public static $plural = "posts";
	public static $singular = "post";

	function __construct() {
		parent::__construct();
		parent::__fk("author_id", "User", "author");
	}
}
class User extends DBObject {
	public static $plural = "users";
	public static $singular = "user";

	function __construct() {
		parent::__construct();
	}
}


$post = Post::find_by_id(1);
echo $post->get('title') . "<br />";
echo $post->get('content');
//$post->set('title', 'Como cuidar de um macaco! 2');
//$post->set('author', 1);
echo $post->get('title');
var_dump($post->get('author')->get('name'));
var_dump($post->save());
echo $post->id();

//var_dump(Post::where('post_id = 1'));
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
