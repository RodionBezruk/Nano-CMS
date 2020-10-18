<?php
if (!defined('IN_ACP3'))
	exit;
if (!$cache->check('categories_files')) {
	$cache->create('categories_files', $db->select('id, name, description', 'categories', 'module = \'files\''));
}
$categories = $cache->output('categories_files');
if (count($categories) > 0) {
	$tpl->assign('categories', $categories);
}
$content = $tpl->fetch('files/list.html');
?>
