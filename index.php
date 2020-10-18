<?php
ob_start();
require 'includes/common.php';
header('Content-Type: text/html; charset=' . CHARSET);
$tpl->assign('lang', CONFIG_LANG);
$tpl->assign('page_title', CONFIG_TITLE);
$tpl->assign('keywords', CONFIG_META_KEYWORDS);
$tpl->assign('description', CONFIG_META_DESCRIPTION);
if (CONFIG_MAINTENANCE == '1' && defined('IN_ACP3')) {
	$tpl->assign('maintenance_msg', CONFIG_MAINTENANCE_MSG);
	$tpl->display('offline.html');
} else {
	if (!isset($_COOKIE['ACP3_AUTH'])) {
		if (defined('IN_ADM') && $modules->mod != 'users' && $modules->page != 'login')
			redirect('acp/users/login');
		session_start();
		$_SESSION['acp3_access'] = '1';
		include 'modules/users/sidebar.php';
		$tpl->assign('login_switch', $field);
	} else {
		$cookie = $db->escape($_COOKIE['ACP3_AUTH']);
		$cookie_arr = explode('|', $cookie);
		$is_user = false;
		$user_check = $db->select('id, pwd, access', 'users', 'name=\'' . $cookie_arr[0] . '\'');
		if (count($user_check) > 0) {
			$user_check[0]['pwd'] = substr($user_check[0]['pwd'], 0, 40);
			if ($user_check[0]['pwd'] == $cookie_arr[1]) {
				$is_user = true;
				session_start();
				if (empty($_SESSION['acp3_id']) || empty($_SESSION['acp3_access'])) {
					$_SESSION['acp3_id'] = $user_check[0]['id'];
					$_SESSION['acp3_access'] = $user_check[0]['access'];
				}
				include 'modules/system/sidebar.php';
				$tpl->assign('login_switch', $field);
			}
		}
		if (!$is_user) {
			include 'modules/users/signoff.php';
		}
	}
	if ($modules->check('pages', 'functions')) {
		include_once 'modules/pages/functions.php';
		$tpl->assign('navbar', process_navbar());
	}
	if ($modules->check() && $modules->page != 'info') {
		$content = '';
		include 'modules/' . $modules->mod . '/' . $modules->page . '.php';
		$tpl->assign('content', $content);
	} elseif (file_exists('modules/errors/404.php')) {
		redirect('errors/404');
	}
	$tpl->assign('title', $breadcrumb->output(2));
	$tpl->assign('breadcrumb', $breadcrumb->output());
	$tpl->display('layout.html');
}
ob_end_flush();
?>
