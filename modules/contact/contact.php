<?php
if (!defined('IN_ACP3'))
	exit;
if (isset($_POST['submit'])) {
	include 'modules/contact/entry.php';
}
if (!isset($_POST['submit']) || isset($errors) && is_array($errors)) {
	if ($auth->is_user() && preg_match('/\d/', $_SESSION['acp3_id'])) {
		$user = $auth->getUserInfo('mail');
		$disabled = ' readonly="readonly" class="readonly"';
		if (isset($form)) {
			$form['mail_disabled'] = $disabled;
		} else {
			$use['mail_disabled'] = $disabled;
		}
		$tpl->assign('form', isset($form) ? $form : $user);
	} else {
		$tpl->assign('form', isset($form) ? $form : array('mail_disabled' => ''));
	}
	$content = $tpl->fetch('contact/contact.html');
}
?>
