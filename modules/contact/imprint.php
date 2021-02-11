<?php
if (!defined('IN_ACP3'))
	exit;
$breadcrumb->assign(lang('contact', 'contact'), uri('contact'));
$breadcrumb->assign(lang('contact', 'imprint'));
$contact = $config->output('contact');
$contact['address'] = $db->escape($contact['address'], 3);
$contact['disclaimer'] = $db->escape($contact['disclaimer'], 3);
$contact['miscellaneous'] = $db->escape($contact['miscellaneous'], 3);
$tpl->assign('imprint', $contact);
$tpl->assign('powered_by', sprintf(lang('contact', 'powered_by'), '<a href="http:
$content = $tpl->fetch('contact/imprint.html');
?>
