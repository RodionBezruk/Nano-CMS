<?php
if (!defined('IN_ACP3'))
	exit;
$breadcrumb->assign(lang('guestbook', 'guestbook'), uri('guestbook'));
$breadcrumb->assign(lang('guestbook', 'create'));
if (isset($_POST['submit'])) {
	include 'modules/guestbook/entry.php';
}
if (!isset($_POST['submit']) || isset($errors) && is_array($errors)) {
	if ($modules->check('emoticons', 'functions')) {
		include_once 'modules/emoticons/functions.php';
		$tpl->assign('emoticons', emoticons_list());
	}
	if ($auth->is_user() && preg_match('/\d/', $_SESSION['acp3_id'])) {
		$user = $auth->getUserInfo('nickname, mail');
		$disabled = ' readonly="readonly" class="readonly"';
		if (isset($form)) {
			$form['name'] = $user['nickname'];
			$form['name_disabled'] = $disabled;
			$form['mail_disabled'] = $disabled;
		} else {
			$user['name'] = $user['nickname'];
			unset($user['nickname']);
			$user['name_disabled'] = $disabled;
			$user['mail_disabled'] = $disabled;
		}
		$tpl->assign('form', isset($form) ? $form : $user);
	} else {
		$defaults['name_disabled'] = '';
		$defaults['mail_disabled'] = '';
		$tpl->assign('form', isset($form) ? $form : $defaults);
	}
	$content = $tpl->fetch('guestbook/create.html');
}
?>
