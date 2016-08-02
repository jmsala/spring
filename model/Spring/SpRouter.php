<?php
namespace Spring;

/**
 * SpRouter class takes the requested URL, check if controller exists and 
 * instantiates it.
 * 
 * Exceptions:
 * 110 - Invalid controller path
 * 111 - Default controller (index) does not exist
 * 112 - Action does not exist in controller 
 */
class SpRouter
{
	private $path = null;
	private $file = null;
	private $controller = null;
	private $action = null;
	private $args = array ();
	
	/**
	 * Set controllers path
	 * @param string absolute controllers directory path
	 * @return object
	 */
	public function setPath($path) {
		$path = rtrim($path, '/\\');
		$path .= DS;

		if (is_dir($path) == false) {
			throw new SpException('Invalid controller path: ' . $path, 110);
		}

		$this->path = $path;
		return $this;
	}
	
	/**
	 * Takes the request and calls the controller and action.
	 * @throws SpException
	 * @return void
	 */
	public function delegate() 
	{
		// Analyze route
		$this->getController();

		// File available?
		if (is_readable($this->file) == false) { 
			throw new SpException ('File Not Found', 111);
		}

		// Include the file
		include ($this->file);

		// Initiate the class
		$class = ucfirst($this->controller) . 'Controller';
		$controller = new $class();

		// Fix sintax of nicer urls: Converts do-some-action to doSomeAction
		$action = $this->dashesToCamelCase($this->action);
		
		// Action available?
		if (is_callable(array ($controller, $action)) == false) {
			throw new SpException ('Action Not Found', 112);
		}

		// Run action
		$controller->$action($this->args);
	}
	
	/**
	 * Analize the request and determines controller/action and params.
	 * @return void
	 */
	private function getController() 
	{
		// If no route, use index as default
		$route = isset($_GET['route']) ? $_GET['route'] : 'index';
		
		// Get separate parts
		$route = trim($route, '/\\');
		$parts = explode('/', $route);

		// Find right controller
		$cmd_path = $this->path;
		foreach ($parts as $part) {
			$fullpath = $cmd_path . $part;

			// Is there a dir with this path?
			if (is_dir($fullpath)) {
				$cmd_path .= $part . DS;
				array_shift($parts);
				continue;
			}

			// Find the file
			if (is_file($fullpath . '.php')) {
				$controller = $part;
				array_shift($parts);
				break;
			}
		}

		if (empty ($controller)) {
			$controller = 'index';
		}

		// Get action
		$action = array_shift($parts);
		if (empty ($action)) {
			$action = 'index';
		}

		$file = $cmd_path . $controller . '.php';
		$args = $parts;
		
		$this->file = $file;
		$this->controller = $controller;
		$this->action = $action;
		$this->args = $args;
	}
	
	/**
	 * Convert a dashed string to a camel case string: do-some-action to doSomeAction
	 * @param string The string to be modified
	 * @param bool First char on the string is lowercase by default
	 * @return camel cased string
	 */
	private function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
	{
	
		$str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	
		if (!$capitalizeFirstCharacter) {
			$str[0] = strtolower($str[0]);
		}
	
		return $str;
	}
}
?>
