<?php
if (!defined('IN_ADM'))
	exit;
if (isset($_POST['submit'])) {
	include 'modules/newsletter/entry.php';
}
if (!isset($_POST['submit']) || isset($error_msg)) {
	$tpl->assign('error_msg', isset($error_msg) ? $error_msg : '');
	$settings = $config->output('newsletter');
	$tpl->assign('form', isset($form) ? $form : $settings);
	$content = $tpl->fetch('newsletter/settings.html');
}
?>
