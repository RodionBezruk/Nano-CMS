<?php
if (!defined('IN_ACP3') && !defined('IN_ADM'))
	exit;
$active_modules = $modules->active_modules();
$nav_mods = array();
foreach ($active_modules as $name => $dir) {
	if ($modules->check($dir, 'adm_list')) {
		if ($dir == 'system') {
			$tpl->assign('access_system', true);
		} elseif ($dir == 'home') {
			$tpl->assign('access_home', true);
		} else {
			$nav_mods[$name]['name'] = $name;
			$nav_mods[$name]['dir'] = $dir;
		}
	}
}
$tpl->assign('nav_mods', $nav_mods);
$field = $tpl->fetch('system/sidebar.html');
?>
