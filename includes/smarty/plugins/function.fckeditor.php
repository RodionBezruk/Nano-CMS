<?php
function smarty_function_fckeditor($params, &$smarty) {
	if (!isset($params['InstanceName']) || empty($params['InstanceName']))
		$smarty->trigger_error('fckeditor: required parameter "InstanceName" missing');
	global $db;
	static $base_arguments = array(), $config_arguments = array();
	$init = count($base_arguments) ? true : false;
	$base_arguments['BasePath'] = ROOT_DIR . 'includes/fckeditor/';
	$base_arguments['InstanceName'] = 'form[' . $params['InstanceName'] . ']';
	if (isset($params['Value']))
		$base_arguments['Value'] = $params['Value'];
	if (isset($params['Width']))
		$base_arguments['Width'] = $params['Width'];
	if (isset($params['Height']))
		$base_arguments['Height'] = $params['Height'];
	if (isset($params['ToolbarSet']))
		$base_arguments['ToolbarSet'] = $params['ToolbarSet'];
	if (isset($params['CheckBrowser']))
		$base_arguments['CheckBrowser'] = $params['CheckBrowser'];
	if (isset($params['DisplayErrors']))
		$base_arguments['DisplayErrors'] = $params['DisplayErrors'];
	$other_arguments = array_diff_assoc($params, $base_arguments);
	$config_arguments = array_merge($config_arguments, $other_arguments);
	$out = '';
	if (!$init)
		$out.= '<script type="text/javascript" src="' . $base_arguments['BasePath'] . 'fckeditor.js"></script>' . "\n";
	$out.= '<script type="text/javascript">' . "\n";
	$out.= '
	$out.= 'var oFCKeditor = new FCKeditor(\'' . $base_arguments['InstanceName'] . '\');' . "\n";
	foreach ($base_arguments as $key => $value) {
		if (!is_bool($value))
			$value = '"' . str_replace("\r\n", "\" + \n\"", $db->escape($value, 2)) . '"';
		$out.= 'oFCKeditor.' . $key . ' = ' . $value . ';' . "\n";
	}
	foreach ($config_arguments as $key => $value) {
		if (!is_bool($value))
			$value = '"' . str_replace("\r\n", "\" + \n\"", $db->escape($value, 2)) . '"';
		$out.= 'oFCKeditor.Config[\'' . $key . '\'] = ' . $value . ';' . "\n";
	}
	$out.= 'oFCKeditor.Create();' . "\n";
	$out.= '
	$out.= '</script>' . "\n";
	$out.= '<noscript>' . "\n";
	$out.= '<textarea name="' . $base_arguments['InstanceName'] . '" id="' . substr($base_arguments['InstanceName'], 5, strlen($base_arguments['InstanceName']) - 6) . '" cols="50" rows="5">' . $base_arguments['Value'] . "</textarea>\n";
	$out.= '</noscript>' . "\n";
	return $out;
}
?>
