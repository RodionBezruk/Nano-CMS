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
					if (!empty($this->end)) {
						$tpl->assign('end', $this->end);
					} elseif ($modules->page == 'list' || $modules->page == 'entry') {
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
			if ($mode == 1) {
				$breadcrumb[0]['uri'] = uri('acp');
				$breadcrumb[0]['title'] = lang('common', 'acp');
				$c_steps = count($this->steps);
				if (($modules->page == 'adm_list' || $modules->page == 'entry') && $c_steps == 0) {
					$tpl->assign('end', lang($modules->mod, $modules->mod));
				} else {
					if ($c_steps > 0) {
						$breadcrumb = array_merge($breadcrumb, $this->steps);
						$tpl->assign('end', $this->end);
					} else {
						$breadcrumb[1]['uri'] = uri('acp/' . $modules->mod);
						$breadcrumb[1]['title'] = lang($modules->mod, $modules->mod);
						$tpl->assign('end', lang($modules->mod, $modules->page));
					}
				}
				$tpl->assign('breadcrumb', $breadcrumb);
				return $tpl->fetch('common/breadcrumb.html');
			}
			else {
				return $modules->page == 'adm_list' || $modules->page == 'entry' ? lang($modules->mod, $modules->mod) : lang($modules->mod, $modules->page);
			}
		}
		return '';
	}
}
?>
