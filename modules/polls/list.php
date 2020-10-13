<?php
if (!defined('IN_ACP3'))
	exit;
$polls = $db->select('id, start, end, question', 'poll_question', 'start <= \'' . date_aligned(2, time()) . '\'', 'end DESC');
$c_polls = count($polls);
if ($c_polls > 0) {
	for ($i = 0; $i < $c_polls; $i++) {
		$polls[$i]['question'] = $polls[$i]['question'];
		if ($db->select('poll_id', 'poll_votes', 'poll_id = \''. $polls[$i]['id'] . '\' AND ip = \'' . $_SERVER['REMOTE_ADDR'] . '\'', 0, 0, 0, 1) == 1 || $polls[$i]['start'] != $polls[$i]['end'] && $polls[$i]['end'] <= date_aligned(2, time()))
			$polls[$i]['link'] = 'result';
		else
			$polls[$i]['link'] = 'vote';
		$polls[$i]['votes'] = $db->select('poll_id', 'poll_votes', 'poll_id = \'' . $polls[$i]['id'] . '\'', 0, 0, 0, 1);
		$polls[$i]['date'] = date_aligned(1, $polls[$i]['end']);
	}
	$tpl->assign('polls', $polls);
}
$content = $tpl->fetch('polls/list.html');
?>
