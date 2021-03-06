<?php
if (!defined('IN_ADM'))
	exit;
$breadcrumb->assign(lang('system', 'system'), uri('acp/system'));
$breadcrumb->assign(lang('system', 'maintenance'), uri('acp/system/maintenance'));
$breadcrumb->assign(lang('system', 'update_check'));
$file = @file_get_contents('http:
if ($file) {
	$content = explode('||', $file);
	$content[2] = CONFIG_VERSION;
	if (version_compare($content[2], $content[0], '>=')) {
		$tpl->assign('update_text', lang('system', 'acp3_up_to_date'));
	} else {
		$tpl->assign('update_text', sprintf(lang('system', 'acp3_not_up_to_date'), '<a href="' . $content[1] . '" onclick="window.open(this.href); return false">', '</a>'));
	}
	$tpl->assign('update', $content);
}
$content = $tpl->fetch('system/update_check.html');
?>
