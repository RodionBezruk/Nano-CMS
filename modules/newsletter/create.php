<?php
if (!defined('IN_ACP3'))
	exit;
if (isset($_POST['submit'])) {
	include 'modules/newsletter/entry.php';
}
if (!isset($_POST['submit']) || isset($errors) && is_array($errors)) {
	$tpl->assign('form', isset($form) ? $form : '');
	$content = $tpl->fetch('newsletter/create.html');
}
?>
