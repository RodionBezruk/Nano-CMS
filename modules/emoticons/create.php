<?php
if (!defined('IN_ADM'))
	exit;
if (isset($_POST['submit'])) {
	include 'modules/emoticons/entry.php';
}
if (!isset($_POST['submit']) || isset($error_msg)) {
	$tpl->assign('error_msg', isset($error_msg) ? $error_msg : '');
	$tpl->assign('form', isset($form) ? $form : '');
	$content = $tpl->fetch('emoticons/create.html');
}
?>
