<?php
function lang($key)
{
	$path = 'languages/' . LANG . '/lang.php';
	if (!defined($key) && is_file($path))
		include_once $path;
	return defined($key) ? str_replace('\n', '<br />', constant($key)) : strtoupper('{' . $key . '}');
}
function mask($var, $mode = 1)
{
	switch ($mode) {
		case 1:
			return htmlspecialchars($var, ENT_QUOTES, CHARSET);
			break;
		case 2:
			return addslashes($var);
			break;
		case 3:
			return stripslashes($var);
			break;
	}
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
function write_config($module, $data)
{
	$path = '../modules/' . $module . '/config.php';
	if (!preg_match('=/=', $module) && is_file($path)) {
		$content = '<?php' . "\n" . '$settings = ' . var_export($data, true) . ';' . "\n" . '?>';
		$bool = @file_put_contents($path, $content);
		return $bool ? true : false;
	}
	return false;
}
?>
