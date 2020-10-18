<?php
function comments_count($entry_id, $module = 0)
{
	global $db, $modules;
	$module = !empty($module) ? $module : $modules->mod;
	return $db->select('id', 'comments', 'module = \'' . $module . '\' AND entry_id =\'' . $entry_id . '\'', 0, 0, 0, 1);
}
function comments_form($url = 0, $module = 0, $entry_id = 0)
{
	global $db, $modules, $tpl;
	$url = !empty($url) ? $url : htmlspecialchars($_SERVER['REQUEST_URI']) . 'action_create/';
	$module = !empty($module) ? $module : $modules->mod;
	$entry_id = !empty($entry_id) ? $entry_id : $modules->id;
	$tpl->assign('com_form', array('url' => $url, 'module' => $module, 'entry_id' => $entry_id));
	if ($modules->check('emoticons', 'functions')) {
		include_once 'modules/emoticons/functions.php';
		$tpl->assign('emoticons', emoticons_list());
	}
	return $tpl->fetch('comments/create.html');
}
function comments_list($module = 0, $entry_id = 0)
{
	global $db, $modules, $tpl;
	$module = !empty($module) ? $module : $modules->mod;
	$entry_id = !empty($entry_id) ? $entry_id : $modules->id;
	$comments = $db->select('name, date, message', 'comments', 'module = \'' . $module . '\' AND entry_id = \'' . $entry_id . '\'', 'date ASC', POS, CONFIG_ENTRIES);
	$c_comments = count($comments);
	$emoticons = false;
	if ($modules->check('emoticons', 'functions')) {
		include_once 'modules/emoticons/functions.php';
		$emoticons = true;
	}
	if ($c_comments > 0) {
		$tpl->assign('pagination', pagination($db->select('id', 'comments', 'module = \'' . $module . '\' AND entry_id = \'' . $entry_id . '\'', 0, 0, 0, 1)));
		for ($i = 0; $i < $c_comments; $i++) {
			$comments[$i]['name'] = $comments[$i]['name'];
			$comments[$i]['date'] = date_aligned(1, $comments[$i]['date']);
			$comments[$i]['message'] = str_replace(array("\r\n", "\r", "\n"), '<br />', $comments[$i]['message']);
			if ($emoticons) {
				$comments[$i]['message'] = emoticons_replace($comments[$i]['message']);
			}
		}
		$tpl->assign('comments', $comments);
	}
	return $tpl->fetch('comments/list.html');
}
?>
