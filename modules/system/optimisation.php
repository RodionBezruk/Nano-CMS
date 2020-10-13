<?php
if (!defined('IN_ADM'))
	exit;
if (!$modules->check())
	redirect('errors/403');
$action = $modules->action == 'do' ? true : false;
$tpl->assign('action', $action);
if ($action) {
	$overall_overhead = 0;
	$table_status = $db->query('SHOW TABLE STATUS FROM ' . CONFIG_DB);
	$c_table_status = count($table_status);
	for($i = 0; $i < $c_table_status; $i++) {
		$overhead_row = round($table_status[$i]['Data_free'] / 1024, 3);
		$overall_overhead = $overall_overhead + $overhead_row;
		if ($overhead_row == 0) {
			$table_status[$i]['status'] = lang('system', 'not_optimised');
			$table_status[$i]['overhead'] = 0;
		} else {
			$db->query('OPTIMIZE TABLE ' . $table_status[$i]['Name'], 3);
			$table_status[$i]['status'] = lang('system', 'optimised');
			$table_status[$i]['overhead'] = $overhead_row;
		}
	}
	$tpl->assign('table_status', $table_status);
	$tpl->assign('overall_overhead', $overall_overhead);
}
$content = $tpl->fetch('system/optimisation.html');
?>
