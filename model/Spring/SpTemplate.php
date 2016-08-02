<?php
namespace Spring;

/**
 * Class SpTemplate
 * 
 * Exceptions:
 * 510 - Trying to set a variable that already exists and overwrite is not allowed
 * 511 - Requested template does not exist
 */
class SpTemplate 
{
	private $vars = array ();

	/**
	 * Assign a variable and its value to a template object.
	 * If overwrite is not allowed, trying to set a value for an existing
	 * variable will throw an exception
	 * @param string Name of the variable
	 * @param mixed Value of the variable
	 * @param bool Overwrite is allowed
	 * @return bool
	 * @throws exception
	 */
	function assign($varname, $value, $overwrite = false) 
	{
		if (isset($this->vars[$varname]) AND $overwrite == false) {
			throw new SpException("Unable to set var '{$varname}'. Already set, and overwrite not allowed.", 510);
		}

		$this->vars[$varname] = $value;
		return true;
	}
	
	/**
	 * Remove variable from template
	 * @param string Name of the variable
	 * @return bool
	 */
	function remove($varname) 
	{
		unset ($this->vars[$varname]);
		return true;
	}
	
	/**
	 * Load a template specified by $name
	 * @param string $name
	 * @throws SpException
	 */
	public function show($name = 'index') 
	{
		$path = SITE_PATH . 'templates' . DS . $name . '.php';
		
		if (file_exists($path) == false) {
			throw new SpException("Template '{$name}' does not exist.", 511);
		}
		
		// Load variables
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}
		
		// Show the template
		include $path;
	}
	
	/**
	 * Fetches content of a template to be included in the main template displayed by show()
	 * @param string $template
	 * @throws SpException
	 */
	public function fetch($template = null)
	{
		if ($template == null) return;
		
		$path = SITE_PATH . 'templates' . DS . $template . '.php';
		
		if (file_exists($path) == false) {
			throw new SpException('Template `' . $template . '` does not exist.', 511);
		}
		
		// run the template
		ob_start();
		
		// Load variables
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}
		require $path;
		
		return ob_get_clean();
	}

}
?>
