<?php
namespace Spring;

/**
 * SpAttribute object represents a table attribute, this is a column.
 * It stores name, value, type and other properties.
 * 
 * Exceptions:
 * 310 - Attribute cannot be created without a name
 */
class SpAttribute
{
	protected $name = null;
	protected $value = null;
	protected $type = null;
	protected $is_nullable = null;
	
	public function __construct($name = null)
	{
		if ($name == null) {
			throw new SpException('Attribute needs a name', 310);
		}
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function setValue($value)
	{
		return $this->value = $value;
	}
	
	public function setType($type)
	{
		return $this->type = $type;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getIsNullable()
	{
		return $this->is_nullable;
	}
	
	public function setIsNullable($nullable)
	{
		return $this->is_nullable = $nullable;
	}
}
?>
