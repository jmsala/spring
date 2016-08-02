<?php
namespace Spring;

class SpValidator
{
	static public function validEmail($email = null)
	{
		$pattern = '/\\A(?:^([a-z0-9][a-z0-9_\\-\\.\\+]*)@([a-z0-9][a-z0-9\\.\\-]{0,63}\\.(com|org|net|biz|info|name|net|pro|aero|coop|museum|[a-z]{2,4}))$)\\z/i';
		return (bool)preg_match($pattern, $email);
	}
	
	static public function validNumber($number)
	{
		$pattern = '/^[-+]?\\b[0-9]*\\.?[0-9]+\\b$/';
		return (bool)preg_match($pattern, $number); 
	}
	
	static public function notEmpty($string)
	{
		$pattern = '/.+/';
		return (bool)preg_match($pattern, $string);
	}
}
?>
