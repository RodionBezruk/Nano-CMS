<?php
if (!defined('IN_ACP3'))
	exit;
if (isset($modules->gen['feed'])) {
	$module = $modules->gen['feed'];
	$link = 'http:
	$rss['link'] = $link . ROOT_DIR;
	$rss['description'] = lang($module, $module);
	$tpl->assign('rss', $rss);
	if (isset($module) && $modules->is_active($module)) {
		include 'modules/feeds/modules/' . $module . '.php';
	}
	define('CUSTOM_CONTENT_TYPE', 'application/xml');
	define('CUSTOM_LAYOUT', 'feeds/rss.html');
} else {
	redirect(0, ROOT_DIR);
}
?>
