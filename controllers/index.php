<?php
use Spring\SpController,
	Spring\SpTemplate;

class IndexController extends SpController
{
	public function index()
	{
		$tpl = new SpTemplate();
		$tpl->show('home');
	}
	
	// IndexController should not contain any more methods
	// Create new controllers to manage requests
}
?>
