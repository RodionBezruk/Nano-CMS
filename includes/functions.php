<?php
function combo_box($text, $forward = 0, $back = 0)
{
	global $tpl;
	$tpl->assign('text', $text);
	if (!empty($forward))
		$tpl->assign('forward', $forward);
	if (!empty($back))
		$tpl->assign('back', $back);
	return $tpl->fetch('common/combo.html');
}
function date_aligned($mode, $time_stamp, $format = 0)
{
	$offset = CONFIG_TIME_ZONE + (CONFIG_DST == '1' ? 3600 : 0);
	if ($mode == 1) {
		$format = !empty($format) ? $format : CONFIG_DATE;
		return gmdate($format, $time_stamp + $offset);
	} elseif ($mode == 2) {
		return gmdate('U', $time_stamp);
	} elseif ($mode == 3 && is_array($time_stamp)) {
		$hour = $time_stamp[0] * 3600;
		$min = $time_stamp[1] * 60;
		$seconds = $hour + $min - $offset;
		return gmmktime(0, 0, $seconds, $time_stamp[3], $time_stamp[4], $time_stamp[5]);
	}
	return false;
}
function date_dropdown($mode, $name, $id, $value = '')
{
	global $tpl;
	$time = date_aligned(1, time(), 'Y');
	$date_arr = array(
		'day' => 'j|1|31',
		'month' => 'n|1|12',
		'year' => 'Y|' . ($time - 6) . '|' . ($time + 3),
		'hour' => 'G|0|23',
		'min' => 'i|0|59'
	);
	$tpl->assign('mode', $mode);
	$tpl->assign('name', $name);
	$tpl->assign('id', $id);
	$date = explode('|', $date_arr[$mode]);
	$loop = NULL;
	for ($date[1]; $date[1] <= $date[2]; $date[1]++) {
		$loop[$date[1]]['current'] = $date[1];
		$time = !ereg('[0-9]', $value) ? date_aligned(1, time(), $date[0]) : $value;
		$loop[$date[1]]['selected'] = select_entry($name, $date[1], $time);
	}
	$tpl->assign('loop', $loop);
	return $tpl->fetch('common/date.html');
}
function lang($mod, $key)
{
	$path = 'languages/' . CONFIG_LANG . '/' . $mod . '.php';
	if (!defined($mod . '_' . $key) && is_file($path))
		include_once $path;
	return defined($mod . '_' . $key) ? str_replace('\n', '<br />', constant($mod . '_' . $key)) : strtoupper('{' . $mod . '_' . $key . '}');
}
function move_file($tmp_filename, $filename, $dir)
{
	$ext = strrchr($filename, '.');
	$path = 'files/' . $dir . '/';
	$new_name = 1;
	while (file_exists($path . $new_name . $ext)) {
		$new_name++;
	}
	if (is_writable($path)) {
		if (!@move_uploaded_file($tmp_filename, $path . $new_name . $ext)) {
			echo sprintf(lang('common', 'upload_error'), $filename);
		} else {
			$new_file['name'] = $new_name . $ext;
			$new_file['size'] = filesize($path . $new_file['name']) / 1024 / 1024;
			$new_file['size'] = round($new_file['size'], 3);
			return $new_file;
		}
	}
}
function pagination($rows)
{
	global $modules, $tpl;
	if ($rows > CONFIG_ENTRIES) {
		$acp = defined('IN_ADM') ? 'acp/' : '';
		$id = !empty($modules->id) ? '/id_' . $modules->id : '';
		$cat = !empty($modules->cat) ? '/cat_' . $modules->cat : '';
		$gen = '';
		if (!empty($modules->gen)) {
			foreach ($modules->gen as $key => $value) {
				$gen.= '/' . $key . '_' . $value;
			}
		}
		$tpl->assign('uri', uri($acp . $modules->mod . '/' . $modules->page . $id . $cat . $gen));
		$c_pages = ceil($rows / CONFIG_ENTRIES);
		$recent = 0;
		for ($i = 1; $i <= $c_pages; $i++) {
			$pages[$i]['selected'] = POS == $recent ? true : false;
			$pages[$i]['page'] = $i;
			$pages[$i]['pos'] = 'pos_' . $recent . '/';
			$recent = $recent + CONFIG_ENTRIES;
		}
		$tpl->assign('pages', $pages);
		$pos_prev = array(
			'pos' => POS - CONFIG_ENTRIES >= 0 ? 'pos_' . (POS - CONFIG_ENTRIES) . '/' : '',
			'selected' => POS == 0 ? true : false,
		);
		$tpl->assign('pos_prev', $pos_prev);
		$pos_next = array(
			'pos' => 'pos_' . (POS + CONFIG_ENTRIES) . '/',
			'selected' => POS + CONFIG_ENTRIES >= $rows ? true : false,
		);
		$tpl->assign('pos_next', $pos_next);
		return $tpl->fetch('common/pagination.html');
	}
}
function redirect($args, $new_page = 0)
{
	header('Location:' . (empty($args) && !empty($new_page) ? str_replace('&amp;', '&', $new_page) : uri($args)));
	exit;
}
function salt($str_length)
{
	$chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
	$c_chars = count($chars) - 1;
	$key = '';
	for ($i = 0; $i < $str_length; ++$i) {
		$key.= $chars[mt_rand(0, $c_chars)];
	}
	return $key;
}
function select_entry($name, $value, $field_value = '', $attr = 'selected') {
	$attr = ' ' . $attr . '="' . $attr . '"';
	if (!isset($_POST['form'][$name])) {
		if (!is_array($field_value) && $field_value == $value) {
			return $attr;
		} elseif (is_array($field_value)) {
			foreach ($field_value as $row) {
				if ($row == $value)
					return $attr;
			}
		}
	} elseif (isset($_POST['form'][$name]) && $_POST['form'][$name] != '') {
		if (!is_array($_POST['form'][$name]) && $_POST['form'][$name] == $value) {
			return $attr;
		} elseif (is_array($_POST['form'][$name])) {
			foreach ($_POST['form'][$name] as $row) {
				if ($row == $value)
					return $attr;
			}
		}
	}
}
function uri($uri)
{
	$pre = CONFIG_SEF == '0' ? PHP_SELF . '?stm=' : ROOT_DIR;
	return $pre . $uri . (!ereg('\/$', $uri) ? '/' : '');
}
?>
