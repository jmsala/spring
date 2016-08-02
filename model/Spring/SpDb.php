<?php
namespace Spring;

if (!defined('CREOLE_DIR')) define('CREOLE_DIR', dirname(__FILE__) . '/../libs/creole/');
require_once CREOLE_DIR . 'Creole.php';

class SpDb
{
	public $conn = null;
	
	public function __construct()
	{
		$this->conn = Creole::getConnection(DB_DSN);
	}
}
?>
