<?php
class modules
{
	public $id = '0';
	public $cat = '0';
	public $action = '';
	public $gen = array();
	function __construct()
	{
		if (!empty($_GET['stm']) && preg_match('/^(acp\/)/', $_GET['stm'])) {
			define('IN_ADM', true);
			$_GET['stm'] = substr($_GET['stm'], 4, strlen($_GET['stm']));
		} else {
			define('IN_ACP3', true);
		}
		$stm = !empty($_GET['stm']) ? explode('/', $_GET['stm']) : null;
		$def_mod = defined('IN_ADM') ? 'home' : 'news';
		$def_page = defined('IN_ADM') ? 'adm_list' : 'list';
		$this->mod = !empty($stm[0]) ? $stm[0] : $def_mod;
		$this->page = !empty($stm[1]) ? $stm[1] : $def_page;
		$this->id = '0';
		$this->cat = !empty($_POST['cat']) ? $_POST['cat'] : '0';
		$this->action = !empty($_POST['action']) ? $_POST['action'] : $this->page;
		if (!empty($stm[2])) {
			$c_stm = count($stm);
			$pos_regex = '/^(pos_(\d+))$/';
			$id_regex = '/^(id_(\d+))$/';
			$cat_regex = '/^(cat_(\d+))$/';
			$action_regex = '/^(action_(\w+))$/';
			$gen_regex = '/^(([a-z0-9-]+)_(.+))$/';
			for ($i = 2; $i < $c_stm; $i++) {
				if (!empty($stm[$i])) {
					if (!defined('POS') && preg_match($pos_regex, $stm[$i]))
						define('POS', substr($stm[$i], 4));
					if (preg_match($id_regex, $stm[$i])) {
						$this->id = substr($stm[$i], 3);
					} elseif (preg_match($cat_regex, $stm[$i])) {
						$this->cat = substr($stm[$i], 4);
					} elseif (preg_match($action_regex, $stm[$i])) {
						$this->action = substr($stm[$i], 7);
					} elseif (preg_match($gen_regex, $stm[$i])) {
						$pos = strpos($stm[$i], '_');
						$this->gen[substr($stm[$i], 0, $pos)] = substr($stm[$i], $pos + 1, strlen($stm[$i]));
					}
				}
			}
		}
		if (!defined('POS')) {
			define('POS', '0');
		}
	}
	function check($module = 0, $page = 0) {
		global $auth, $db;
		static $access_level = array();
		$module = !empty($module) ? $module : $this->mod;
		$page = !empty($page) ? $page : $this->page;
		if (isset($_SESSION) && is_file('modules/' . $module . '/' . $page . '.php')) {
			$xml = simplexml_load_file('modules/' . $module . '/module.xml');
			if ((string) $xml->info->active == '1') {
				if (!isset($access_level[$module])) {
					$access_id = 2;
					if (isset($_SESSION['acp3_id'])) {
						$info = $auth->getUserInfo('access');
						if (!empty($info)) {
							$access_id = $info['access'];
						}
					}
					$access_to_modules = $db->select('modules', 'access', 'id = \'' . $access_id . '\'');
					$modules = explode(',', $access_to_modules[0]['modules']);
					foreach ($modules as $row) {
						$access_level[substr($row, 0, -2)] = substr($row, -1, 1);
					}
				}
				if ($page == 'entry') {
					foreach ($xml->xpath('
						if ((string) $action->name == $this->action && (string) $action->level != '0' && isset($access_level[$module]) && (string) $action->level <= $access_level[$module]) {
							return true;
						}
					}
				} else {
					foreach ($xml->access->item as $item) {
						if ((string) $item->file == $page && (string) $item->level != '0' && isset($access_level[$module]) && (string) $item->level <= $access_level[$module]) {
							return true;
						}
					}
				}
			}
		}
		return false;
	}
	function modulesList()
	{
		$modules_dir = scandir('modules/');
		$mod_list = array();
		foreach ($modules_dir as $module) {
			$info = $this->parseInfo($module);
			if (is_array($info)) {
				$name = $info['name'];
				$mod_list[$name] = $info;
				$mod_list[$name]['dir'] = $module;
			}
		}
		ksort($mod_list);
		return $mod_list;
	}
	function parseInfo($module)
	{
		$path = 'modules/' . $module . '/module.xml';
		if (!preg_match('=/=', $module) && is_file($path)) {
			$xml = simplexml_load_file($path);
			$info = $xml->info;
			$mod_info = array();
			$mod_info['author'] = (string) $info->author;
			$mod_info['description'] = (string) $info->description['lang'] == 'true' ? lang($module, 'mod_description') : (string) $info->description;
			$mod_info['name'] = (string) $info->name['lang'] == 'true' ? lang($module, $module) : (string) $info->name;
			$mod_info['version'] = (string) $info->version['core'] == 'true' ? CONFIG_VERSION : (string) $info->version;
			$mod_info['active'] = (string) $info->active;
			$mod_info['categories'] = isset($info->categories) ? true : false;
			$mod_info['protected'] = $info->protected ? true : false;
			return $mod_info;
		}
		return false;
	}
}
?>
