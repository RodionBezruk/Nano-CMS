<?php
if (!defined('IN_ADM'))
	exit;
if (isset($_POST['submit'])) {
	include 'modules/categories/entry.php';
}
if (!isset($_POST['submit']) || isset($errors) && is_array($errors)) {
	$tpl->assign('form', isset($form) ? $form : '');
	$mod_list = $modules->modulesList();
	foreach ($mod_list as $name => $info) {
		if ($info['active'] && $info['categories']) {
			$mod_list[$name]['selected'] = select_entry('module', $info['dir']);
		} else {
			unset($mod_list[$name]);
		}
	}
	$tpl->assign('mod_list', $mod_list);
	$content = $tpl->fetch('categories/create.html');
}
?>
