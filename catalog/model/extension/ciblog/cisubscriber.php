<?php
class ModelExtensionCiBlogCiSubscriber extends Model {
	public function getCiSubscriber($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_subscriber WHERE email = '" . $this->db->escape($email) . "'");
		return $query->row;
	}

	public function addCiSubscriber($email) {
		$status=0;
		$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_subscriber SET email = '" . $this->db->escape($email) . "', status='". (int)$status ."', date_added=NOW()");

		$ciblog_subscriber_id = $this->db->getLastId();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_subscriber WHERE email = '" . $this->db->escape($email) . "'");

		foreach ($query->rows as $row) {
			$this->sendSubscribeVerificationCode($row);
		}

		return $ciblog_subscriber_id;
	}

	public function sendVerificationCode($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_subscriber WHERE email = '" . $this->db->escape($email) . "', verification_status!='CONFIRMED'");
		if($query->num_rows) {
			foreach ($query->rows as $row) {
				if($row['action_requested'] == 'UNSUBSCRIBE') {
					$this->sendSubscribeVerificationCode($row);
				}
				if($row['action_requested'] == 'SUBSCRIBE') {
					$this->sendUnSubscribeVerificationCode($row);
				}
			}
		}
	}

	public function sendSubscribeVerificationCode($row) {
		$email = $row['email'];

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		// send email for verification
		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$logo =  $server . 'image/' . $this->config->get('config_logo');
		} else {
			$logo = '';
		}

		$this->load->language('extension/ciblog/cimail_subscriber');

		$subject = $this->language->get('text_subject');

		$message = $this->language->get('text_welcome');


		$expire_hours = 24;
		$verification_status = 'PENDING';
		$action_requested = 'SUBSCRIBE';

		$message = $this->language->get('text_approval');

		do {
			$verification_code = token(10);
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_subscriber WHERE `code`='". $this->db->escape($verification_code) ."'");
		}while($query->row['total']>0);


		$verification_url = $this->url->link('extension/ciblog/cisubscriber/verify', '', true);
		$validation_url = $this->url->link('extension/ciblog/cisubscriber/verify', 'key=' . $verification_code, true);

		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_subscriber SET `code`='". $this->db->escape($verification_code) ."', date_generated=NOW(), expire_hours='". (int)$expire_hours ."', verification_status='". $this->db->escape($verification_status) ."', action_requested='". $this->db->escape($action_requested) ."' WHERE `email`='". $this->db->escape($email) ."'");


		$find = array(
			'{LOGO}',
			'{STORE_NAME}',
			'{STORE_LINK}',
			'{VALIDATION_URL}',
			'{EMAIL}',
			'{VERIFICATION_URL}',
			'{VERIFICATION_CODE}',
		);

		$replace = array(
			'LOGO'					=> '<img src="'. $logo .'" alt="'. htmlentities($this->config->get('config_name')) .'" title="'. htmlentities($this->config->get('config_name')) .'" />',
			'STORE_NAME'			=> html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'),
			'STORE_LINK'			=> $this->url->link('common/home', '', true),
			'VALIDATION_URL'		=> $validation_url,
			'EMAIL'					=> $email,
			'VERIFICATION_URL'		=> $verification_url,
			'VERIFICATION_CODE'		=> $verification_code,
		);

		$subject = str_replace($find, $replace, $subject);

		$message = str_replace($find, $replace, $message);


		$mail_data['title'] = $subject;
		$mail_data['description'] = $message;


		$message = $this->load->view('extension/ciblog/cimail_subscriber', $mail_data);

		$mail = $this->ciblog->mail();

		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($message);
		$mail->send();
	}

	public function sendUnSubscribeVerificationCode($row) {
		$email = $row['email'];
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		// send email for verification
		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$logo =  $server . 'image/' . $this->config->get('config_logo');
		} else {
			$logo = '';
		}

		$this->load->language('extension/ciblog/cimail_subscriber');

		$subject = $this->language->get('text_unsubscribe_subject');

		$message = $this->language->get('text_unsubscribe_message');


		$expire_hours = 24;
		$verification_status = 'PENDING';
		$action_requested = 'UNSUBSCRIBE';


		do {
			$verification_code = token(10);
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_subscriber WHERE `code`='". $this->db->escape($verification_code) ."'");
		}while($query->row['total']>0);


		$verification_url = $this->url->link('extension/ciblog/cisubscriber/verify', '', true);
		$validation_url = $this->url->link('extension/ciblog/cisubscriber/verify', 'key=' . $verification_code, true);

		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_subscriber SET `code`='". $this->db->escape($verification_code) ."', date_generated=NOW(), expire_hours='". (int)$expire_hours ."', verification_status='". $this->db->escape($verification_status) ."', action_requested='". $this->db->escape($action_requested) ."' WHERE `email`='". $this->db->escape($email) ."'");


		$find = array(
			'{LOGO}',
			'{STORE_NAME}',
			'{STORE_LINK}',
			'{VALIDATION_URL}',
			'{EMAIL}',
			'{VERIFICATION_URL}',
			'{VERIFICATION_CODE}',
		);

		$replace = array(
			'LOGO'					=> '<img src="'. $logo .'" alt="'. htmlentities($this->config->get('config_name')) .'" title="'. htmlentities($this->config->get('config_name')) .'" />',
			'STORE_NAME'			=> html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'),
			'STORE_LINK'			=> $this->url->link('common/home', '', true),
			'VALIDATION_URL'		=> $validation_url,
			'EMAIL'					=> $email,
			'VERIFICATION_URL'		=> $verification_url,
			'VERIFICATION_CODE'		=> $verification_code,
		);

		$subject = str_replace($find, $replace, $subject);

		$message = str_replace($find, $replace, $message);


		$mail_data['title'] = $subject;
		$mail_data['description'] = $message;


		$message = $this->load->view('extension/ciblog/cimail_subscriber', $mail_data);

		$mail = $this->ciblog->mail();

		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($message);
		$mail->send();
	}

	public function ciUnSubscribe($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_subscriber WHERE email = '" . $this->db->escape($email) . "'");
		if($query->num_rows) {
			foreach ($query->rows as $row) {
				$this->sendUnSubscribeVerificationCode($row);
			}
		}
	}

	public function verifyCode($verification_code) {
		$verification_status = 'PENDING';
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_subscriber WHERE `code`='". $this->db->escape($verification_code) ."' AND status=0 AND  verification_status='". $this->db->escape($verification_status) ."'");
		return $query->rows;
	}

	public function updateCode($ciblog_subscriber_id, $action_taken, $verification_status) {
		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_subscriber SET verification_status='". $this->db->escape($verification_status) ."', action_taken='". $this->db->escape($action_taken) ."' WHERE `ciblog_subscriber_id`='". (int)$ciblog_subscriber_id ."'");
	}

	public function updateSubscriberStatus($ciblog_subscriber_id, $status=0) {
		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_subscriber SET status='". (int)$status ."' WHERE `ciblog_subscriber_id`='". (int)$ciblog_subscriber_id ."'");
	}
}