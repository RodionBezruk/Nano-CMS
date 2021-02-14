<?php
if (!defined('IN_ADM'))
	exit;
$breadcrumb->assign(lang('system', 'system'), uri('acp/system'));
$breadcrumb->assign(lang('system', 'maintenance'));
$content = $tpl->fetch('system/maintenance.html');
?>
