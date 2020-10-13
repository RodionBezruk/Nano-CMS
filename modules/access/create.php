<?php
if (!defined('IN_ADM'))
	exit;
if (!$modules->check())
	redirect('errors/403');
if (isset($_POST['submit'])) {
	include 'modules/access/entry.php';
}
if (!isset($_POST['submit']) || isset($error_msg)) {
	$tpl->assign('error_msg', isset($error_msg) ? $error_msg : '');
	$tpl->assign('form', isset($form) ? $form : '');
	$mods = $db->select('module', 'modules', 'active = \'1\'');
	$c_mods = count($mods);
	for($i = 0; $i < $c_mods; $i++) {
		$mods[$i]['module'] = $db->escape($mods[$i]['module'], 3);
		if ($modules->check(1, $mods[$i]['module'], 'adm_list')) {
			include('modules/' . $mods[$i]['module'] . '/info.php');
			$name = $mod_info['name'];
			$mod_list[$name]['dir'] = $mods[$i]['module'];
			$mod_list[$name]['selected'] = select_entry('modules', $mods[$i]['module']);
			$mod_list[$name]['name'] = $name;
		}
	}
	ksort($mod_list);
	$tpl->assign('mod_list', $mod_list);
	$content = $tpl->fetch('access/create.html');
}
?>
