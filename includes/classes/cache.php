<?php
class cache
{
	function check($filename)
	{
		if (is_file('cache/sql_' . md5($filename) . '.php')) {
			return true;
		}
		return false;
	}
	function create($filename, $sql_results)
	{
		if (!empty($sql_results)) {
			$content = '<?php' . "\n";
			$content.= 'if (!defined(\'IN_ACP3\') && !defined(\'IN_ADM\'))' . "\n";
			$content.= "\t" . 'exit;' . "\n\n";
			$content.= '$results = ' . var_export($sql_results, true) . ';' . "\n";
			$content.= '?>';
			$bool = @file_put_contents('cache/sql_' . md5($filename) . '.php', $content);
			return $bool ? true : false;
		} elseif ($this->check($filename)) {
			$this->delete($filename);
			return true;
		}
		return false;
	}
	function delete($filename)
	{
		if ($this->check($filename)) {
			return unlink('cache/sql_' . md5($filename) . '.php');
		}
		return false;
	}
	function output($filename)
	{
		if ($this->check($filename)) {
			$results = array();
			include 'cache/sql_' . md5($filename) . '.php';
			return $results;
		}
		return null;
	}
	function purge()
	{
		$cache_dir = scandir('cache');
		$c_cache_dir = count($cache_dir);
		for ($i = 0; $i < $c_cache_dir; $i++) {
			if (is_file('cache/' . $cache_dir[$i]) && $cache_dir[$i] != '.htaccess') {
				unlink('cache/' . $cache_dir[$i]);
			}
		}
		return;
	}
}
?>
