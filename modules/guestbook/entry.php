<?php
if (!defined('IN_ACP3') && !defined('IN_ADM'))
	exit;
if (!$modules->check('guestbook', 'entry'))
	redirect('errors/403');
switch ($modules->action) {
	case 'create':
		$ip = $_SERVER['REMOTE_ADDR'];
		$form = $_POST['form'];
		$flood = $db->select('date', 'guestbook', 'ip = \'' . $ip . '\'', 'id DESC', '1');
		if (count($flood) == '1') {
			$flood_time = $flood[0]['date'] + CONFIG_FLOOD;
		}
		$time = date_aligned(2, time());
		if (isset($flood_time) && $flood_time > $time)
			$errors[] = sprintf(lang('common', 'flood_no_entry_possible'), $flood_time - $time);
		if (empty($form['name']))
			$errors[] = lang('common', 'name_to_short');
		if (!empty($form['mail']) && !$validate->email($form['mail']))
			$errors[] = lang('common', 'wrong_email_format');
		if (strlen($form['message']) < 3)
			$errors[] = lang('common', 'message_to_short');
		if (isset($errors)) {
			combo_box($errors);
		} else {
			$insert_values = array(
				'id' => '',
				'ip' => $ip,
				'date' => $time,
				'name' => $db->escape($form['name']),
				'user_id' => $auth->is_user() && preg_match('/\d/', $_SESSION['acp3_id']) ? $_SESSION['acp3_id'] : '',
				'message' => $db->escape($form['message']),
				'website' => $db->escape($form['website'], 2),
				'mail' => $form['mail'],
			);
			$bool = $db->insert('guestbook', $insert_values);
			$content = combo_box($bool ? lang('guestbook', 'create_success') : lang('guestbook', 'create_error'), uri('guestbook'));
		}
		break;
	case 'edit':
		$form = $_POST['form'];
		if (empty($form['name']))
			$errors[] = lang('common', 'name_to_short');
		if (strlen($form['message']) < 3)
			$errors[] = lang('common', 'message_to_short');
		if (isset($errors)) {
			combo_box($errors);
		} else {
			$update_values = array(
				'name' => $db->escape($form['name']),
				'message' => $db->escape($form['message']),
			);
			$bool = $db->update('guestbook', $update_values, 'id = \'' . $modules->id . '\'');
			$content = combo_box($bool ? lang('guestbook', 'edit_success') : lang('guestbook', 'edit_error'), uri('acp/guestbook'));
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
			$content = combo_box(lang('guestbook', 'confirm_delete'), uri('acp/guestbook/adm_list/action_delete/entries_' . $marked_entries), uri('acp/guestbook'));
		} elseif (ereg('^([0-9|]+)$', $entries) && isset($modules->gen['confirmed'])) {
			$marked_entries = explode('|', $entries);
			$bool = 0;
			foreach ($marked_entries as $entry) {
				if (!empty($entry) && ereg('[0-9]', $entry) && $db->select('id', 'guestbook', 'id = \'' . $entry . '\'', 0, 0, 0, 1) == '1')
					$bool = $db->delete('guestbook', 'id = \'' . $entry . '\'');
			}
			$content = combo_box($bool ? lang('guestbook', 'delete_success') : lang('guestbook', 'delete_error'), uri('acp/guestbook'));
		} else {
			redirect('errors/404');
		}
		break;
	default:
		redirect('errors/404');
}
?>
