<?php
if (!defined('IN_ACP3') && !defined('IN_ADM'))
	exit;
setcookie('ACP3_AUTH', '', time() - 3600, '/');
$_SESSION = array();
if (isset($_COOKIE[session_name()]))
	setcookie(session_name(), '', time() - 3600, '/');
session_destroy();
redirect('news/list');
?>
