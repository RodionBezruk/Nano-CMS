<?php
if (!defined('IN_ACP3'))
	exit;
if (isset($modules->gen['feed'])) {
	$module = $modules->gen['feed'];
	$link = 'http:
	$rss['link'] = $link . ROOT_DIR;
	$rss['description'] = lang($module, $module);
	$tpl->assign('rss', $rss);
	if (isset($module) && $modules->check($module, 'extensions/feeds')) {
		include 'modules/' . $module . '/extensions/feeds.php';
	}
	define('CUSTOM_CONTENT_TYPE', 'application/xml');
	define('CUSTOM_LAYOUT', 'feeds/rss.html');
} else {
	redirect(0, ROOT_DIR);
}
?>
