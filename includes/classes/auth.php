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
			$user_check = $db->select('id, pwd, access', 'users', 'nickname = \'' . $cookie_arr[0] . '\'');
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
				setcookie('ACP3_AUTH', '', time() - 3600, ROOT_DIR);
				$_SESSION = array();
				if (isset($_COOKIE[session_name()]))
					setcookie(session_name(), '', time() - 3600, ROOT_DIR);
				session_destroy();
				redirect(0, ROOT_DIR);
			}
		} else {
			$_SESSION['acp3_access'] = '2';
		}
	}
	function getUserInfo($fields, $user_id = 0)
	{
		if (empty($user_id) && $this->is_user) {
			$user_id = $_SESSION['acp3_id'];
		}
		if (preg_match('/\d/', $user_id)) {
			global $db;
			$info = $db->select($fields, 'users', 'id = \'' . $user_id . '\'');
			return count($info) == '1' ? $info[0] : false;
		}
		return false;
	}
	function is_user()
	{
		return $this->is_user;
	}
}
?>
