<?php
namespace Spring;

abstract class SpController
{
	/**
	 * Constructor cannot be overriden.
	 * To execute anything on child classes, implement init()
	 */
	final public function __construct()
	{
		$this->init();
	}
	
	/**
	 * Initialization method.
	 * This is a convenience method to prevent overriding the constructor. Any
	 * child class requiring actions at instantiation time, override this
	 * method
	 */
	protected function init()
	{
	}
	
	/**
	 * Redirects one controller to another.
	 * @param string Route
	 * @return void
	 */
	public function forward($route)
	{
		header("Location: $route");
	}
	
	/**
	 * All child classes must implement this method.
	 * Index method is the default for requests that only specify the controller class.
	 */
	abstract function index();
}
?>
