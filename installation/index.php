<?php
ob_start();
header('Content-type: text/html; charset=UTF-8');
define('IN_INSTALL', true);
error_reporting(E_ALL);
include '../includes/globals.php';
include 'functions.php';
define('ACP3_SKIN', 'design');
define('SMARTY_DIR', '../includes/smarty/');
include SMARTY_DIR . 'Smarty.class.php';
$tpl = new smarty;
$tpl->template_dir = ACP3_SKIN . '/';
$tpl->compile_dir = '../cache/';
define('PHP_SELF', $_SERVER['PHP_SELF']);
$tpl->assign('php_self', PHP_SELF);
$tpl->assign('request_uri', htmlspecialchars($_SERVER['REQUEST_URI']));
define('LANG', !empty($_REQUEST['lang']) && is_file('languages/' . $_REQUEST['lang'] . '/info.php') ? $_REQUEST['lang'] : 'de');
$tpl->assign('lang', LANG);
$mod = !empty($_GET['mod']) && is_dir('modules/' . $_GET['mod'] . '/') ? $_GET['mod'] : 'overview';
if ($mod == 'overview') {
	$page = !empty($_GET['page']) && is_file('modules/' . $mod . '/' . $_GET['page'] . '.php') ? $_GET['page'] : 'welcome';
} elseif ($mod == 'install') {
	$page = !empty($_GET['page']) && is_file('modules/' . $mod . '/' . $_GET['page'] . '.php') ? $_GET['page'] : 'requirements';
}
$navbar['overview'] = array(
	'title' => lang('overview'),
	'page' => 'overview',
	'selected' => '',
);
$navbar['install'] = array(
	'title' => lang('installation'),
	'page' => 'install',
	'selected' => '',
);
if (array_key_exists($mod, $navbar)) {
	$navbar[$mod]['selected'] = ' class="selected"';
}
$tpl->assign('navbar', $navbar);
if ($mod == 'overview') {
	$nav_left[0]['page'] = 'welcome';
	$nav_left[0]['selected'] = '';
	$nav_left[1]['page'] = 'licence';
	$nav_left[1]['selected'] = '';
} elseif ($mod == 'install') {
	$nav_left[0]['page'] = 'requirements';
	$nav_left[0]['selected'] = '';
	$nav_left[0]['no_href'] = true;
	$nav_left[1]['page'] = 'configuration';
	$nav_left[1]['selected'] = '';
	$nav_left[1]['no_href'] = true;
}
$i = 0;
foreach ($nav_left as $row) {
	if ($row['page'] == $page) {
		$nav_left[$i]['selected'] = ' class="selected"';
		$tpl->assign('title', lang($row['page']));
		break;
	}
	$i++;
}
$tpl->assign('nav_left', $nav_left);
$languages = array();
$directories = scandir('languages');
$count_dir = count($directories);
for ($i = 0; $i < $count_dir; $i++) {
	$lang_info = array();
	if ($directories[$i] != '.' && $directories[$i] != '..' && is_file('languages/' . $directories[$i] . '/info.php')) {
		include 'languages/' . $directories[$i] . '/info.php';
		$languages[$i]['dir'] = $directories[$i];
		$languages[$i]['selected'] = LANG == $directories[$i] ? ' selected="selected"' : '';
		$languages[$i]['name'] = $lang_info['name'];
	}
}
$tpl->assign('languages', $languages);
$content = '';
include 'modules/' . $mod . '/' . $page . '.php';
$tpl->assign('content', $content);
$tpl->display('layout.html');
ob_end_flush();
?>
