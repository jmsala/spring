<?php
use Spring\SpRouter,
	Spring\SpTemplate;

try {
	// Startup tasks (define constants, etc)
	require '../config/common.php';

	# Load router
	$router = new SpRouter();
	$router->setPath(SITE_PATH.'controllers')->delegate();
}
catch (exception $e)
{
	header("HTTP/1.0 404 Not Found");
	$tpl = new SpTemplate;
	if (SHOW_EXCEPTIONS == true) {
		$tpl->assign('error_msg', "{$e->getMessage()} [{$e->getCode()}]");
	}
	$tpl->assign('content', $tpl->fetch('error'));
	$tpl->show('index');
}
?>
