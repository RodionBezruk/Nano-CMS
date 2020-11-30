<?php
if (!defined('IN_ACP3'))
	exit;
if (isset($_POST['submit'])) {
	include 'modules/search/entry.php';
}
if (!isset($_POST['submit']) || isset($error_msg)) {
	$tpl->assign('error_msg', isset($error_msg) ? $error_msg : '');
	$tpl->assign('form', isset($form) ? $form : '');
	$mods = scandir('modules/');
	$c_mods = count($mods);
	$search_mods = array();
	for ($i = 0; $i < $c_mods; $i++) {
		if ($modules->check($mods[$i], 'extensions/search')) {
			$info = $modules->parseInfo($mods[$i]);
			$name = $info['name'];
			$search_mods[$name]['dir'] = $mods[$i];
			$search_mods[$name]['checked'] = select_entry('mods', $mods[$i], '', 'checked');
			$search_mods[$name]['name'] = $name;
		}
	}
	ksort($search_mods);
	$tpl->assign('search_mods', $search_mods);
	$search_areas[0]['id'] = 'title_only';
	$search_areas[0]['value'] = 'title';
	$search_areas[0]['checked'] = select_entry('area', 'title', '', 'checked');
	$search_areas[0]['lang'] = lang('search', 'title_only');
	$search_areas[1]['id'] = 'content_only';
	$search_areas[1]['value'] = 'content';
	$search_areas[1]['checked'] = select_entry('area', 'content', '', 'checked');
	$search_areas[1]['lang'] = lang('search', 'content_only');
	$search_areas[2]['id'] = 'title_content';
	$search_areas[2]['value'] = 'title_content';
	$search_areas[2]['checked'] = select_entry('area', 'title_content', '', 'checked');
	$search_areas[2]['lang'] = lang('search', 'title_and_content');
	$tpl->assign('search_areas', $search_areas);
	$sort_hits[0]['id'] = 'asc';
	$sort_hits[0]['value'] = 'asc';
	$sort_hits[0]['checked'] = select_entry('sort', 'asc', '', 'checked');
	$sort_hits[0]['lang'] = lang('search', 'asc');
	$sort_hits[1]['id'] = 'desc';
	$sort_hits[1]['value'] = 'desc';
	$sort_hits[1]['checked'] = select_entry('sort', 'desc', '', 'checked');
	$sort_hits[1]['lang'] = lang('search', 'desc');
	$tpl->assign('sort_hits', $sort_hits);
	$content = $tpl->fetch('search/list.html');
}
?>
