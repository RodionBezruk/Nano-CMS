<?php
if (!defined('IN_ACP3') && !defined('IN_ADM'))
	exit;
if (!$modules->check(0, 'entry'))
	redirect('errors/403');
switch ($modules->action) {
	case 'contact':
		$form = $_POST['form'];
		if (empty($form['name']))
			$errors[] = lang('common', 'name_to_short');
		if (!$validate->email($form['mail']))
			$errors[] = lang('common', 'wrong_email_format');
		if (strlen($form['message']) < 3)
			$errors[] = lang('common', 'message_to_short');
		if (isset($errors)) {
			$error_msg = combo_box($errors);
		} else {
			$contact = $config->output('contact');
			$subject = sprintf(lang('contact', 'contact_subject'), CONFIG_TITLE);
			$body = sprintf(lang('contact', 'contact_body'), $form['name'], $form['mail']) . "\n\n" . $form['message'];
			$bool = @mail($contact[0]['mail'], $subject, $body, 'FROM:' . $form['mail']);
			$content = combo_box($bool ? lang('contact', 'form_success') : lang('contact', 'form_error'), uri('contact/contact'));
		}
		break;
	case 'edit':
		$form = $_POST['form'];
		if (!empty($form['mail']) && !$validate->email($form['mail']))
			$errors[] = lang('common', 'wrong_email_format');
		if (isset($errors)) {
			$error_msg = combo_box($errors);
		} else {
			$form['address'] = $db->escape($form['address'], 2);
			$form['telephone'] = $db->escape($form['telephone']);
			$form['fax'] = $db->escape($form['fax']);
			$form['disclaimer'] = $db->escape($form['disclaimer'], 2);
			$form['miscellaneous'] = $db->escape($form['miscellaneous'], 2);
			$bool = $config->module('contact', $form);
			$content = combo_box($bool ? lang('contact', 'edit_success') : lang('contact', 'edit_error'), uri('acp/contact'));
		}
		break;
	default:
		redirect('errors/404');
}
?>
