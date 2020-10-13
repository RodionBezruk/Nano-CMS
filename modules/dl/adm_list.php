<?php
if (!defined('IN_ADM'))
	exit;
if (!$modules->check())
	redirect('errors/403');
if (isset($_POST['entries']) || isset($modules->gen['entries'])) {
	include 'modules/dl/entry.php';
} else {
	$files = $db->select('id, start, end, file, size, link_title', 'dl', 0, 'start DESC', POS, CONFIG_ENTRIES);
	$c_files = count($files);
	if ($c_files > 0) {
		$tpl->assign('pagination', pagination($db->select('id', 'dl', 0, 0, 0, 0, 1)));
		for ($i = 0; $i < $c_files; $i++) {
			$files[$i]['start'] = date_aligned(1, $files[$i]['start']);
			$files[$i]['end'] = date_aligned(1, $files[$i]['end']);
			$files[$i]['size'] = file_exists('files/dl/' . $files[$i]['file']) ? $files[$i]['size'] . ' MB' : lang('dl', 'unknown_filesize');
			$files[$i]['link_title'] = $files[$i]['link_title'];
		}
		$tpl->assign('files', $files);
	}
	$content = $tpl->fetch('dl/adm_list.html');
}
?>
