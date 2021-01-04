<?php
class auth
{
	private $is_user = false;
	function __construct()
	{
		session_start();
		if (isset($_COOKIE['ACP3_AUTH'])) {
			global $db;
			$cookie = $db->escape($_COOKIE['ACP3_AUTH']);
			$cookie_arr = explode('|', $cookie);
			$user_check = $db->select('id, pwd, access', 'users', 'name = \'' . $cookie_arr[0] . '\'');
			if (count($user_check) == '1') {
				$db_password = substr($user_check[0]['pwd'], 0, 40);
				if ($db_password == $cookie_arr[1]) {
					$this->is_user = true;
					if (empty($_SESSION['acp3_id']) || empty($_SESSION['acp3_access'])) {
						$_SESSION['acp3_id'] = $user_check[0]['id'];
						$_SESSION['acp3_access'] = $user_check[0]['access'];
					}
				}
			}
			if (!$this->is_user) {
				redirect('users/signoff');
			}
		} else {
			$_SESSION['acp3_access'] = '2';
		}
	}
	function is_user()
	{
		return $this->is_user;
	}
}
?>
