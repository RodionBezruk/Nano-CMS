<?php
if (!defined('IN_ACP3') && !defined('IN_ADM'))
	exit;
if (!$modules->check('users', 'entry'))
	redirect('errors/403');
switch ($modules->action) {
	case 'create':
		$form = $_POST['form'];
		if (empty($form['name']))
			$errors[] = lang('common', 'name_to_short');
		if (!empty($form['name']) && $db->select('id', 'users', 'name = \'' . $db->escape($form['name']) . '\'', 0, 0, 0, 1) == '1')
			$errors[] = lang('users', 'user_already_exists');
		if (!$validate->email($form['mail']))
			$errors[] = lang('common', 'wrong_email_format');
		if ($validate->email($form['mail']) && $db->select('id', 'users', 'mail =\'' . $form['mail'] . '\'', 0, 0, 0, 1) > 0)
			$errors[] = lang('common', 'user_email_already_exists');
		if (!ereg('[0-9]', $form['access']))
			$errors[] = lang('users', 'select_access_level');
		if (empty($form['pwd']) || empty($form['pwd_repeat']) || $form['pwd'] != $form['pwd_repeat'])
			$errors[] = lang('users', 'type_in_pwd');
		if (isset($errors)) {
			combo_box($errors);
		} else {
			$salt = salt(12);
			$insert_values = array(
				'id' => '',
				'name' => $db->escape($form['name']),
				'pwd' => sha1($salt . sha1($form['pwd'])) . ':' . $salt,
				'access' => $form['access'],
				'mail' => $form['mail'],
				'draft' => '',
			);
			$bool = $db->insert('users', $insert_values);
			$content = combo_box($bool ? lang('users', 'create_success') : lang('users', 'create_error'), uri('acp/users'));
		}
		break;
	case 'edit':
		$form = $_POST['form'];
		if (empty($form['name']))
			$errors[] = lang('common', 'name_to_short');
		if (!empty($form['name']) && $db->select('id', 'users', 'id != \'' . $modules->id . '\' AND name = \'' . $db->escape($form['name']) . '\'', 0, 0, 0, 1) == '1')
			$errors[] = lang('users', 'user_already_exists');
		if (!$validate->email($form['mail']))
			$errors[] = lang('common', 'wrong_email_format');
		if ($validate->email($form['mail']) && $db->select('id', 'users', 'id != \'' . $modules->id . '\' AND mail =\'' . $form['mail'] . '\'', 0, 0, 0, 1) > 0)
			$errors[] = lang('common', 'user_email_already_exists');
		if (!ereg('[0-9]', $form['access']))
			$errors[] = lang('users', 'select_access_level');
		if (!empty($form['new_pwd']) && !empty($form['new_pwd_repeat']) && $form['new_pwd'] != $form['new_pwd_repeat'])
			$errors[] = lang('users', 'type_in_pwd');
		if (isset($errors)) {
			combo_box($errors);
		} else {
			$new_pwd_sql = null;
			if (!empty($form['new_pwd']) && !empty($form['new_pwd_repeat'])) {
				$salt = salt(12);
				$new_pwd = sha1($salt . sha1($form['new_pwd']));
				$new_pwd_sql = array('pwd' => $new_pwd . ':' . $salt);
			}
			$update_values = array(
				'name' => $db->escape($form['name']),
				'access' => $form['access'],
				'mail' => $form['mail'],
			);
			if (is_array($new_pwd_sql)) {
				$update_values = array_merge($update_values, $new_pwd_sql);
			}
			$bool = $db->update('users', $update_values, 'id = \'' . $modules->id . '\'');
			if ($modules->id == $_SESSION['acp3_id']) {
				$cookie_arr = explode('|', $_COOKIE['ACP3_AUTH']);
				setcookie('ACP3_AUTH', $form['name'] . '|' . (isset($new_pwd) ? $new_pwd : $cookie_arr[1]), time() + 3600, ROOT_DIR);
				$_SESSION['acp3_access'] = $form['access'];
			}
			$content = combo_box($bool ? lang('users', 'edit_success') : lang('users', 'edit_error'), uri('acp/users'));
		}
		break;
	case 'delete':
		if (isset($_POST['entries']) && is_array($_POST['entries']))
			$entries = $_POST['entries'];
		elseif (isset($modules->gen['entries']) && ereg('^([0-9|]+)$', $modules->gen['entries']))
			$entries = $modules->gen['entries'];
		if (is_array($entries)) {
			$marked_entries = '';
			foreach ($entries as $entry) {
				$marked_entries.= $entry . '|';
			}
			$content = combo_box(lang('users', 'confirm_delete'), uri('acp/users/adm_list/action_delete/entries_' . $marked_entries), uri('acp/users'));
		} elseif (ereg('^([0-9|]+)$', $entries) && isset($modules->gen['confirmed'])) {
			$marked_entries = explode('|', $entries);
			$bool = false;
			$admin_user = false;
			$session_user = false;
			foreach ($marked_entries as $entry) {
				if (!empty($entry) && ereg('[0-9]', $entry) && $db->select('id', 'users', 'id = \'' . $entry . '\'', 0, 0, 0, 1) == '1') {
					if ($entry == '1') {
						$admin_user = true;
					} else {
						if ($entry == $_SESSION['acp3_id']) {
							$session_user = true;
						}
						$bool = $db->delete('users', 'id = \'' . $entry . '\'');
					}
				}
			}
			if ($session_user) {
				if (isset($_COOKIE[session_name()])) {
					setcookie(session_name(), '', time() - 3600, ROOT_DIR);
				}
				setcookie('ACP3_AUTH', '', time() - 3600, ROOT_DIR);
				$_SESSION = array();
				session_destroy();
				$check_admin = true;
			}
			if ($admin_user) {
				$text = lang('users', 'admin_user_undeletable');
			} else {
				$text = $bool ? lang('users', 'delete_success') : lang('users', 'delete_error');
			}
			$content = combo_box($text, $session_user ? ROOT_DIR : uri('acp/users'));
		} else {
			redirect('errors/404');
		}
		break;
	case 'edit_profile':
		if (!$auth->is_user() || !preg_match('/\d/', $_SESSION['acp3_id'])) {
			redirect('errors/403');
		} else {
			$form = $_POST['form'];
			if (empty($form['name']))
				$errors[] = lang('common', 'name_to_short');
			if (!empty($form['name']) && $db->select('id', 'users', 'id != \'' . $_SESSION['acp3_id'] . '\' AND name = \'' . $db->escape($form['name']) . '\'', 0, 0, 0, 1) == '1')
				$errors[] = lang('users', 'user_already_exists');
			if (!$validate->email($form['mail']))
				$errors[] = lang('common', 'wrong_email_format');
			if ($validate->email($form['mail']) && $db->select('id', 'users', 'id != \'' . $_SESSION['acp3_id'] . '\' AND mail =\'' . $form['mail'] . '\'', 0, 0, 0, 1) > 0)
				$errors[] = lang('common', 'user_email_already_exists');
			if (!empty($form['new_pwd']) && !empty($form['new_pwd_repeat']) && $form['new_pwd'] != $form['new_pwd_repeat'])
				$errors[] = lang('users', 'type_in_pwd');
			if (isset($errors)) {
				combo_box($errors);
			} else {
				$new_pwd_sql = null;
				if (!empty($form['new_pwd']) && !empty($form['new_pwd_repeat'])) {
					$salt = salt(12);
					$new_pwd = sha1($salt . sha1($form['new_pwd']));
					$new_pwd_sql = array('pwd' => $new_pwd . ':' . $salt);
				}
				$update_values = array(
					'name' => $db->escape($form['name']),
					'mail' => $form['mail'],
				);
				if (is_array($new_pwd_sql)) {
					$update_values = array_merge($update_values, $new_pwd_sql);
				}
				$bool = $db->update('users', $update_values, 'id = \'' . $_SESSION['acp3_id'] . '\'');
				$cookie_arr = explode('|', $_COOKIE['ACP3_AUTH']);
				setcookie('ACP3_AUTH', $form['name'] . '|' . (isset($new_pwd) ? $new_pwd : $cookie_arr[1]), time() + 3600, ROOT_DIR);
				$content = combo_box($bool ? lang('users', 'edit_profile_success') : lang('users', 'edit_profile_error'), uri('users/home'));
			}
		}
		break;
	case 'forgot_pwd':
		$form = $_POST['form'];
		if (empty($form['name']) && empty($form['mail']))
			$errors[] = lang('users', 'type_in_name_and_email');
		if (!empty($form['name']) && $db->select('id', 'users', 'name = \'' . $db->escape($form['name']) . '\'', 0, 0, 0, 1) == '0')
			$errors[] = lang('users', 'user_not_exists');
		if (!empty($form['mail']) && !$validate->email($form['mail']))
			$errors[] = lang('common', 'wrong_email_format');
		if ($validate->email($form['mail']) && $db->select('id', 'users', 'mail = \'' . $form['mail'] . '\'', 0, 0, 0, 1) == '0')
			$errors[] = lang('users', 'user_not_exists');
		if (isset($errors)) {
			combo_box($errors);
		} else {
			$new_password = salt(8);
			$salt = salt(12);
			$where_stmt = !empty($form['mail']) ? 'mail = \'' . $form['mail'] . '\'' : 'name = \'' . $db->escape($form['name']) . '\'';
			$user = $db->select('id, name, mail', 'users', $where_stmt);
			$subject = sprintf(lang('users', 'forgot_pwd_mail_subject'), CONFIG_TITLE, htmlentities($_SERVER['HTTP_HOST']));
			$message = sprintf(lang('users', 'forgot_pwd_mail_message'), $user[0]['name'], CONFIG_TITLE, htmlentities($_SERVER['HTTP_HOST']), $user[0]['mail'], $new_password);
			$header = 'Content-type: text/plain; charset=' . CHARSET;
			$mail_sent = @mail($user[0]['mail'], $subject, $message, $header);
			if ($mail_sent) {
				$update_values = array(
					'pwd' => sha1($salt . sha1($new_password)) . ':' . $salt,
				);
				$bool = $db->update('users', $update_values, 'id = \'' . $user[0]['id'] . '\'');
			}
			$content = combo_box($mail_sent && isset($bool) && $bool ? lang('users', 'forgot_pwd_success') : lang('users', 'forgot_pwd_error'), ROOT_DIR);
		}
		break;
	case 'home':
		if (!$auth->is_user() || !preg_match('/\d/', $_SESSION['acp3_id'])) {
			redirect('errors/403');
		} else {
			$form = $_POST['form'];
			$bool = $db->update('users', array('draft' => $db->escape($form['draft'], 2)), 'id = \'' . $_SESSION['acp3_id'] . '\'');
			$content = combo_box($bool ? lang('users', 'draft_success') : lang('users', 'draft_error'), uri('users/home'));
		}
		break;
	case 'register':
		$form = $_POST['form'];
		if (empty($form['name']))
			$errors[] = lang('common', 'name_to_short');
		if (!empty($form['name']) && $db->select('id', 'users', 'name = \'' . $db->escape($form['name']) . '\'', 0, 0, 0, 1) == '1')
			$errors[] = lang('users', 'user_already_exists');
		if (!$validate->email($form['mail']))
			$errors[] = lang('common', 'wrong_email_format');
		if ($validate->email($form['mail']) && $db->select('id', 'users', 'mail =\'' . $form['mail'] . '\'', 0, 0, 0, 1) > 0)
			$errors[] = lang('common', 'user_email_already_exists');
		if (empty($form['pwd']) || empty($form['pwd_repeat']) || $form['pwd'] != $form['pwd_repeat'])
			$errors[] = lang('users', 'type_in_pwd');
		if (isset($errors)) {
			combo_box($errors);
		} else {
			$salt = salt(12);
			$subject = sprintf(lang('users', 'register_mail_subject'), CONFIG_TITLE, htmlentities($_SERVER['HTTP_HOST']));
			$message = sprintf(lang('users', 'register_mail_message'), $db->escape($form['name']), CONFIG_TITLE, htmlentities($_SERVER['HTTP_HOST']), $form['mail'], $form['pwd']);
			$header = 'Content-type: text/plain; charset=' . CHARSET;
			$mail_sent = @mail($form['mail'], $subject, $message, $header);
			if ($mail_sent) {
				$insert_values = array(
					'id' => '',
					'name' => $db->escape($form['name']),
					'pwd' => sha1($salt . sha1($form['pwd'])) . ':' . $salt,
					'access' => '3',
					'mail' => $form['mail'],
				);
				$bool = $db->insert('users', $insert_values);
			}
			$content = combo_box($mail_sent && isset($bool) && $bool ? lang('users', 'register_success') : lang('users', 'register_error'), ROOT_DIR);
		}
		break;
	default:
		redirect('errors/404');
}
?>
