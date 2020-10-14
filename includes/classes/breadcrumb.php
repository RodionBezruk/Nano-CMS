<?php
class breadcrumb
{
	protected $steps = array();
	protected $end = '';
	function assign($title, $uri = 0)
	{
		static $i = 0;
		if (!empty($uri)) {
			$this->steps[$i]['uri'] = $uri;
			$this->steps[$i]['title'] = $title;
			$i++;
			return;
		} else {
			$this->end = $title;
			return;
		}
	}
	function output($mode = 1)
	{
		global $modules, $tpl;
		if (defined('IN_ACP3')) {
			if ($mode == 1) {
				$c_steps = count($this->steps);
				if ($c_steps > 0) {
					$tpl->assign('breadcrumb', $this->steps);
					$tpl->assign('end', $this->end);
				} else {
					if ($modules->page == 'list' || $modules->page == 'entry') {
						$tpl->assign('end', lang($modules->mod, $modules->mod));
					} else {
						$tpl->assign('end', lang($modules->mod, $modules->page));
					}
				}
				return $tpl->fetch('common/breadcrumb.html');
			} else {
				return $modules->page != 'list' && $modules->page != 'entry' ? lang($modules->mod, $modules->page) : lang($modules->mod, $modules->mod);
			}
		} elseif (defined('IN_ADM')) {
			$breadcrumb[0]['uri'] = uri('acp');
			$breadcrumb[0]['title'] = lang('common', 'acp');
			if ($mode == 1) {
				if ($modules->mod == 'errors' || $modules->page == 'adm_list' || $modules->page == 'entry') {
					$tpl->assign('end', lang($modules->mod, $modules->mod));
				} else {
					$breadcrumb[1]['uri'] = uri('acp/' . $modules->mod);
					$breadcrumb[1]['title'] = lang($modules->mod, $modules->mod);
					$tpl->assign('end', lang($modules->mod, $modules->page));
				}
				$tpl->assign('breadcrumb', $breadcrumb);
				return $tpl->fetch('common/breadcrumb.html');
			}
			else {
				return $modules->mod == 'errors' || $modules->page == 'adm_list' || $modules->page == 'entry' ? lang($modules->mod, $modules->mod) : lang($modules->mod, $modules->page);
			}
		}
		return '';
	}
}
?>
