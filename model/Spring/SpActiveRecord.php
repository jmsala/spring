<?php
namespace Spring;

use Spring\SpDatabase;

/**
 * Exceptions:
 * 210 - Undefined table
 * 211 - Object has no ID
 * 212 - Object data did not pass validation
 * 213 - setAttributes() only works with an array 
 */
abstract class SpActiveRecord
{
	/**
	 * Instance of Db object
	 */
	protected $conn;
	/**
	 * Table of the database that will implement the active record
	 */
	protected $table = null;
	/**
	 * Array of attribute objects with the definition of the table.
	 */
	protected $attributes = array();
	
	public function __construct($id = null)
	{
		$db = new SpDatabase();
		$this->conn = $db;
		
		$this->loadMetadata();
		
		if ($id != null) {
			$this->id = $id;
			$this->loadData();
		}
	}
	
	/**
	 * Load metadata of the table.
	 * Retrieves all fields and types, and stores them as attributes of the object.
	 * @throws SpException
	 * @return void
	 */
	private function loadMetadata()
	{
		if ($this->table == null) {
			throw new SpException('Undefined table', 210);
		}
		$sql = "DESCRIBE {$this->table}";
		
		$this->conn->query($sql);
		$this->conn->execute();
		
		foreach ($this->conn->resultset() as $rs) {
			$name = $rs['Field'];
			$attribute = new SpAttribute($name);
			$attribute->setType($rs['Type']);
			$this->attributes[] = $attribute;
			unset($attribute);
		}
	}
	
	/**
	 * Retrieves a record from the database and populates the object.
	 * @return void 
	 */
	private function loadData()
	{
		$this->conn->query("SELECT * FROM {$this->table} WHERE id = :id");
		$this->conn->bind(':id', $this->id);
		$this->conn->execute();
		
		foreach ($this->conn->resultset() as $row) {
			foreach($row as $attribute => $value) {
				$this->$attribute = $value;
			}
		}
	}
	
	/**
	 * Saves the record to the database.
	 * This is a convenience method that will call insert or update
	 * as required.
	 */
	public function save()
	{
		if ($this->id == null) {
			return $this->insert();
		} else {
			return $this->update();
		}
	}
	
	/**
	 * Inserts a new record represented by the current object.
	 * This function should not be called, use save() instead
	 * 
	 * @param array
	 */
	protected function insert($attributes = null)
	{
		if ($attributes) {
			foreach ($attributes as $key => $value) {
				$this->attributes[$key] = $value;
			}
		}
		
		// Validate data
		if ($this->validate() != true) {
			throw new SpActiveRecordException("Object data did not pass validation.", 212);
		}
		
		$sql = "INSERT INTO {$this->table} (";
		foreach ($this->attributes as $attribute) {
			if ($attribute->getName() == 'id') continue;
			$sql .= "{$attribute->getName()}, ";
		}
		$sql = rtrim($sql, ', ');
		$sql .= ") VALUES (";
		foreach ($this->attributes as $attribute) {
			if ($attribute->getName() == 'id') continue;
			$sql .= ":{$attribute->getName()}, ";
		}
		$sql = rtrim($sql, ', ');
		$sql .= ")";
		
		// Add bindings for security reasons
		foreach ($this->attributes as $attribute) {
			$this->conn->bind(":{$attribute->getName()}", $attribute->getValue());			
		}
		echo $sql;
		$this->conn->execute();
				
		// Get id of the new row and set it in $this->id
		$this->id = $this->conn->lastInsertId();

		return true;
	}
	
	/**
	 * Updates a record represented by the current object.
	 * This function should not be called, use save() instead
	 */
	protected function update()
	{
		if (!$this->id) {
			throw new Spring_Exception("{$this->table} object needs an ID", 211);
		}
		
		// Validate data
		if ($this->validate() != true) {
			throw new SpActiveRecordException("Object data did not pass validation.", 212);
		}
		
		$sql = "UPDATE {$this->table} SET ";
		foreach ($this->attributes as $attribute) {
			// Skip ID
			if ($attribute->getName() == 'id') continue;
			// Mainteinance fields
			if ($attribute->getName() == 'updated_at') {
				$sql .= "{$attribute->getName()} = '".date('Y-m-d h:i:s')."', ";
			} else {
				$sql .= "{$attribute->getName()} = '{$attribute->getValue()}', ";
			}
		}
		
		$sql = rtrim($sql, ', ');
		$sql .= " WHERE id = {$this->id}";
		
		$this->conn->executeQuery($sql);
		
		return;
	}
	
	/**
	 * Deletes the row represented by the current object
	 * @throws SpException
	 * @return void;
	 */
	public function delete()
	{
		if (!$this->id) {
			throw new SpActiveRecordException("Record has no ID. Cannot be deleted", 213);
		}
		
		$sql = "DELETE FROM {$this->table} WHERE id = {$this->id}";
		$rs = $this->conn->executeQuery($sql);
		
		return;
	}
	
	/**
	 * Find all rows from this table.
	 * Returns an array of objects found on the table.
	 * @return array
	 */
	public function findAll()
	{
		$sql = "SELECT id FROM {$this->table}";
		$rs = $this->conn->executeQuery($sql);
		
		$class = get_class($this);
		
		$found = array();
		while ($rs->next()) {
			$item = new $class($rs->getInt('id'));
			$found[] = $item;
			unset($item);
		}
		
		return $found;
	}
	
	/**
	 * Return object of selected record
	 * @param int Id of record
	 * @return object
	 */
	public function findByPk($id)
	{
		$this->id = (int)$id;
		$this->loadData();
		
		return $this;
	}
	
	/**
	 * Returns first record matching conditions
	 * @param array conditions
	 * @param string order by column
	 * @return
	 */
	public function findFirst($conditions = array(), $order = null)
	{
		$sql = "SELECT * FROM {$this->table} ";
		
		// Add conditions
		if (!empty($conditions)) {
			$sql .= "WHERE ";
			foreach ($conditions as $condition => $value) {
				$sql .= "$condition = :$condition ";
			}
		}
		
		// Add order
		if ($order != null) $sql .= "ORDER BY {$order} ";
		// Limit results to only 1
		$sql .= "LIMIT 1";

		$this->conn->query($sql);
		if (!empty($conditions)) {
			foreach ($conditions as $condition => $value) {
				$this->conn->bind(":$condition", $value);
			}
		}
		
		// Get array of results
		$found = $this->findBySql($sql);
		
		// Return first element
		return $found[0];
	}
	
	/**
	 * Returns objects specified by a complete SQL string
	 * @param string sql string
	 * @return mixed Array of objects or false if none returned
	 */
	public function findBySql($sql = null)
	{
		if ($sql == null) return false;

		$this->conn->execute($sql);
		
		// If records found, store the objects in an array
		if ($this->conn->rowCount() > 0) {
			$class = get_class($this);
			foreach ($this->conn->resultset() as $rs) {
				$id = $rs['id'];
				$return[] = new $class($id);
			}
			return $return;
		}
		return false;
	}
	
	/**
	 * Validates object data before inserting/updating record.
	 * To implement validation, the child class must override this
	 * method and include the required checks. Then must return 
	 * true or false if validation is passed or not. If child class
	 * has no validate() method, by default will be validated as true.
	 * @return bool
	 */
	protected function validate()
	{
		return true;
	}
	
	/**
	 * Converts an assoc array to an object
	 * @param array $array
	 * @param string $class
	 */
	protected static function to_object(array $array, $class = 'stdClass')
	{
		$object = new $class;
		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				// Convert the array to an object
				$value = arr::to_object($value, $class);
			}
			// Add the value to the object
			$object->{$key} = $value;
		}
		return $object;
	}
	
	/**
	 * Takes an array of values and fills the object.
	 * Array must be associative, having attribute name as key and attribute
	 * value as value
	 * @param array Associative array of attributes and values
	 * @throws SpActiveRecordException
	 * @return void
	 */
	public function setAttributes($attributes)
	{
		if (!is_array($attributes)) {
			throw new SpActiveRecordException('Setting multiple attributes requires an array of values', 213);
		}
		
		foreach ($attributes as $attribute_name => $attribute_value) {
			$this->$attribute_name = $attribute_value;
		}
	}
	
	/**
	 * Sets attribute values
	 * @param string attribute name
	 * @param mixed value of the attribute
	 * @return void
	 */
	public function __set($attribute, $value)
	{
		foreach ($this->attributes as $index => $attribute_object) {
			if ($attribute_object->getName() == $attribute) {
				$this->attributes[$index]->setValue($value);
			}
		}
	}
	
	/**
	 * Gets attribute value
	 * @param string attribute name
	 * @return mixed value of attribute
	 */
	public function __get($attribute)
	{
		foreach ($this->attributes as $index => $attribute_object) {
			if ($attribute_object->getName() == $attribute) {
				return $this->attributes[$index]->getValue();
			}
		}
	}
	
	/**
	 * Creates a representation of the current object
	 * @return string
	 */
	public function __toString() {
		$string = "Object <strong>".get_class($this)."</strong><br />";

		foreach ($this->attributes as $attribute => $value) {
			$string .= "{$value->getName()}: {$value->getValue()}";
			$string .= "<br />";
		}

		return $string;
	}
}
?>
