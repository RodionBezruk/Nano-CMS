<?php
ob_start();
require 'includes/common.php';
if (CONFIG_MAINTENANCE == '1')
	redirect(0, 'index.php');
if ($modules->check(1, 'feeds', 'info')) {
	header('Content-Type: application/xml; charset=' . CHARSET);
	$mode = !empty($_GET['mode']) ? $_GET['mode'] : 'news';
	$path = 'http:
	$path = htmlentities($path, ENT_QUOTES);
	$rss['title'] = CONFIG_TITLE;
	$rss['path'] = $path;
	$rss['description'] = lang($mode, $mode);
	$rss['language'] = CONFIG_LANG;
	$tpl->assign('rss', $rss);
	if ($modules->check(1, $mode, 'info'))
		include 'modules/feeds/modules/' . $mode . '.php';
	$tpl->display('feeds/feeds.html');
}
ob_end_flush();
?>
