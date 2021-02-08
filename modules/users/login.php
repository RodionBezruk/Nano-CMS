<?php
if (!defined('IN_ACP3') && !defined('IN_ADM'))
	exit;
if ($auth->is_user()) {
	redirect(0, ROOT_DIR);
} elseif (isset($_POST['submit'])) {
	$form = $_POST['form'];
	$user = $db->select('id, pwd', 'users', 'nickname = \'' . $db->escape($form['nickname']) . '\'');
	$auth = false;
	if (count($user) == '1') {
		$db_hash = substr($user[0]['pwd'], 0, 40);
		$salt = substr($user[0]['pwd'], 41, 53);
		$form_pwd_hash = sha1($salt . sha1($form['pwd']));
		if ($db_hash == $form_pwd_hash) {
			$auth = true;
		}
	}
	if ($auth) {
		$expire = isset($_POST['remember']) ? 31104000 : 3600;
		setcookie('ACP3_AUTH', $db->escape($form['nickname']) . '|' . $db_hash, time() + $expire, ROOT_DIR);
		$_SESSION['acp3_id'] = $user[0]['id'];
		if (isset($form['redirect_uri'])) {
			redirect(0, base64_decode($form['redirect_uri']));
		} else {
			redirect(0, ROOT_DIR);
		}
	} else {
		$tpl->assign('error', lang('users', 'nickname_or_password_wrong'));
	}
}
$content = $tpl->fetch('users/login.html');
?>
