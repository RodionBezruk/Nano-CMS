<?php
function smarty_function_lang($params, &$smarty)
{
	$values = explode('|', $params['values']);
	return lang($values[0], $values[1]);
}
?>
