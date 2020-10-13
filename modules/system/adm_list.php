<?php
if (!defined('IN_ADM'))
	exit;
if (!$modules->check())
	redirect('errors/403');
$content = $tpl->fetch('system/adm_list.html');
?>
