<?php
class validate
{
	function date($values, $prefix_start = 'start', $prefix_end = 'end')
	{
		if (!ereg('[0-9]', $values[$prefix_start . '_day']) ||
			!ereg('[0-9]', $values[$prefix_start . '_month']) ||
			!ereg('[0-9]', $values[$prefix_start . '_year']) ||
			!ereg('[0-9]', $values[$prefix_start . '_hour']) ||
			!ereg('[0-9]', $values[$prefix_start . '_min']) ||
			!ereg('[0-9]', $values[$prefix_end . '_day']) ||
			!ereg('[0-9]', $values[$prefix_end . '_month']) ||
			!ereg('[0-9]', $values[$prefix_end . '_year']) ||
			!ereg('[0-9]', $values[$prefix_end . '_hour']) ||
			!ereg('[0-9]', $values[$prefix_end . '_min']) ||
			mktime($values[$prefix_start . '_hour'], $values[$prefix_start . '_min'], 0, $values[$prefix_start . '_month'], $values[$prefix_start . '_day'], $values[$prefix_start . '_year']) >
			mktime($values[$prefix_end . '_hour'], $values[$prefix_end . '_min'], 0, $values[$prefix_end . '_month'], $values[$prefix_end . '_day'], $values[$prefix_end . '_year']))
			return false;
		return true;
	}
	function email($var)
	{
		$pattern = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/';
		return preg_match($pattern, $var) ? true : false;
	}
	function is_picture($var)
	{
		$info = getimagesize($var);
		return $info[2] == '1' || $info[2] == '2' || $info[2] == '3' ? true : false;
	}
}
?>
