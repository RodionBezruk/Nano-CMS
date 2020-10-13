<?php
class config
{
	function general($data)
	{
		$path = 'includes/config.php';
		if (is_writable($path))	{
			$config = file($path);
			foreach ($data as $key => $value) {
				$old_entry = 'define(\'CONFIG_' . strtoupper($key) . '\', \'' . constant('CONFIG_' . strtoupper($key)) . '\');' . "\n";
				$new_entry = 'define(\'CONFIG_' . strtoupper($key) . '\', \'' . $value . '\');' . "\n";
				foreach ($config as $c_key => $c_value) {
					if ($old_entry == $c_value && $new_entry != $c_value) {
						$config[$c_key] = $new_entry;
					}
				}
			}
			$bool = @file_put_contents($path, $config);
			return $bool ? true : false;
		}
		return false;
	}
	function module($module, $data)
	{
		$path = 'modules/' . $module . '/config.php';
		if (!preg_match('=/=', $module) && is_file($path)) {
			$content = '<?php' . "\n" . '$settings = ' . var_export($data, true) . ';' . "\n" . '?>';
			$bool = @file_put_contents($path, $content);
			return $bool ? true : false;
		}
		return false;
	}
	function output($module)
	{
		$path = 'modules/' . $module . '/config.php';
		if (!preg_match('=/=', $module) && is_file($path)) {
			$settings = array();
			require_once $path;
			return $settings;
		}
		return false;
	}
}
?>
