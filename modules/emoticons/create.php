<?php
if (!defined('IN_ADM'))
	exit;
if (isset($_POST['submit'])) {
	include 'modules/emoticons/entry.php';
}
if (!isset($_POST['submit']) || isset($errors) && is_array($errors)) {
	$tpl->assign('form', isset($form) ? $form : '');
	$content = $tpl->fetch('emoticons/create.html');
}
?>
