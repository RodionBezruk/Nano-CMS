<?php
if (!defined('IN_ADM'))
	exit;
if (isset($_POST['entries']) || isset($modules->gen['entries'])) {
	include 'modules/news/entry.php';
} else {
	$news = $db->select('n.id, n.start, n.end, n.headline, c.name AS cat', 'news AS n, ' . CONFIG_DB_PRE . 'categories AS c', 'n.category_id = c.id', 'n.start DESC', POS, CONFIG_ENTRIES);
	$c_news = count($news);
	if ($c_news > 0) {
		$tpl->assign('pagination', pagination($db->select('id', 'news', 0, 0, 0, 0, 1)));
		for ($i = 0; $i < $c_news; $i++) {
			$news[$i]['start'] = date_aligned(1, $news[$i]['start']);
			$news[$i]['end'] = date_aligned(1, $news[$i]['end']);
		}
		$tpl->assign('news', $news);
	}
	$content = $tpl->fetch('news/adm_list.html');
}
?>
