<?php
if (!defined('IN_ACP3'))
	exit;
$date = ' AND (start = end AND start <= \'' . date_aligned(2, time()) . '\' OR start != end AND start <= \'' . date_aligned(2, time()) . '\' AND end >= \'' . date_aligned(2, time()) . '\')';
if (!empty($modules->id) && $db->select('id', 'pages', 'id = \'' . $modules->id . '\'' . $date, 0, 0, 0, 1) == 1) {
	if (!$cache->check('pages_list_id_' . $modules->id)) {
		$cache->create('pages_list_id_' . $modules->id, $db->select('mode, uri, text', 'pages', 'id = \'' . $modules->id . '\''));
	}
	$page = $cache->output('pages_list_id_' . $modules->id);
	class breadcrumb_pages extends breadcrumb
	{
		function output($mode = 1, $id = 0)
		{
			global $modules, $tpl;
			$id = !empty($id) ? $id : $modules->id;
			if (!empty($id)) {
				global $db;
				$page = $db->select('parent, title', 'pages', 'id = \'' . $id . '\' AND mode = \'1\'');
				if ($mode == 1) {
					if (empty($this->end)) {
						$this->end = $page[0]['title'];
					}
					if ($db->select('parent', 'pages', 'id = \'' . $page[0]['parent'] . '\' AND mode = \'1\'', 0, 0, 0, 1) > 0) {
						$parent = $db->select('title', 'pages', 'id = \'' . $page[0]['parent'] . '\' AND mode = \'1\'');
						$this->assign($parent[0]['title'], uri('pages/list/id_' . $page[0]['parent']));
						return $this->output(1, $page[0]['parent']);
					}
					$pages = $this->steps;
					krsort($pages);
					$tpl->assign('breadcrumb', $pages);
					$tpl->assign('end', $this->end);
					return $tpl->fetch('common/breadcrumb.html');
				} else {
					return $page[0]['title'];
				}
			}
			return '';
		}
	}
	if ($page[0]['mode'] == '1') {
		unset($breadcrumb);
		$breadcrumb = new breadcrumb_pages;
		$tpl->assign('text', $db->escape($page[0]['text'], 3));
	} elseif ($page[0]['mode'] == '2') {
		redirect($page[0]['uri']);
	} else {
		redirect(0, $db->escape($page[0]['uri'], 3));
	}
} else {
	redirect('errors/404');
}
$content = $tpl->fetch('pages/list.html');
?>
