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
			switch($fk['type']) {
				case 'belongs_to':
					if(isset($this->attrs[$fk['field']])) {
						return $fk['class']::find_by_id($this->attrs[$fk['field']]);
					}
				break;
				case 'has_many':
					return $fk['class']::where($fk['field'] . ' = '. $this->id());
				break;
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
			$values[$field] = "{$field} = {$val}";
		}
		unset($values[$this->pk()]);
		$values['created_at'] = 'NOW()';

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
			$values[$field] = "{$field} = {$val}";
		}
		unset($values[$this->pk()]);
		$values['updated_at'] = 'NOW()';

		$string = implode(', ', $values);
		return Database::query("UPDATE {$this->plural()} SET {$string} WHERE {$this->pk()} = {$this->id()};");
	}
	public function set_and_save($field, $value) {
		$this->attrs[$field] = $value;
		return save();
	}
	
	public function destroy() {
		$delete = Database::query("DELETE FROM {$this->plural()} WHERE {$this->pk()} = {$this->id()};");
	#	if($delete) {
	#		if(unset($object)) {
	#		return true;
	#		}
	#	}
		unset($this);
		return $delete;
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

	protected function __belongs_to($class, $field, $label) {
		$this->fks[$label] = array('class' => $class, 'field' => $field, 'type' => 'belongs_to');
	}
	protected function __has_many($class, $field, $label) {
		$this->fks[$label] = array('class' => $class, 'field' => $field, 'type' => 'has_many');
	}

	# not implemented #	
	# protected function __mas_many($class, $label) {
	# 	$this->fks[$label] = array('class' => $class, 'type' => 'n');
	# }
}

?>