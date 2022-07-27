<?php
class ModelExtensionCiBlogCiComment extends Model {
	public function stripTag(&$s) {

		do {
			$s = htmlentities($s, ENT_QUOTES, 'UTF-8');

			$s = str_replace('&amp;', '&', $s);

			$s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');

		} while (strpos($s, '&amp;') !== false);


		$s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');

		$s = strip_tags($s);


	}
	public function addCiComment($ciblog_post_id, $data) {

		$this->stripTag($data['cibc_author']);
		$this->stripTag($data['cibc_email']);
		$this->stripTag($data['cibc_text']);

		$status = 0;
		if($this->customer->isLogged() && $this->config->get('ciblog_store_blogpage_comment_approve')=='LOGGED') {
			$status = 1;
		} elseif($this->config->get('ciblog_store_blogpage_comment_approve')=='BOTH') {
			$status = 1;
		}



		$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_comment SET author = '" . $this->db->escape($data['cibc_author']) . "', email = '" . $this->db->escape($data['cibc_email']) . "', customer_id = '" . (int)$this->customer->getId() . "', ciblog_post_id = '" . (int)$ciblog_post_id . "', text = '" . $this->db->escape($data['cibc_text']) . "', rating = '" . (int)$data['cibc_rating'] . "', language_id = '" . (int)$this->config->get('config_language_id') . "', store_id = '" . (int)$this->config->get('config_store_id') . "', status = '". $status ."', date_added = NOW()");


		$ciblog_comment_id = $this->db->getLastId();

		$admin_email = $this->config->get('config_email');

		$send_author_email = false;
		if($send_author_email) {
			$this->load->language('extension/ciblog/cimail_comment');
			$this->load->model('extension/ciblog/ciblogpost');

			$ciblog_post_info = $this->model_extension_ciblog_ciblogpost->getCiBlogPost($ciblog_post_id);

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = $this->language->get('text_submit') . "\n";
			$message .= sprintf($this->language->get('text_ciblog_post'), html_entity_decode($ciblog_post_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_your_name'), html_entity_decode($data['cibc_author'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_rating'), html_entity_decode($data['cibc_rating'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= $this->language->get('text_comment') . "\n";
			$message .= html_entity_decode($data['cibc_text'], ENT_QUOTES, 'UTF-8') . "\n\n";


			$mail = $this->ciblog->mail();

			$mail->setTo($data['cibc_email']);
			$mail->setReplyTo($admin_email);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
		}


		$send_admin_email = false;

		if($send_admin_email) {
			$this->load->language('extension/ciblog/cimail_comment');
			$this->load->model('extension/ciblog/ciblogpost');

			$ciblog_post_info = $this->model_extension_ciblog_ciblogpost->getCiBlogPost($ciblog_post_id);

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = $this->language->get('text_waiting') . "\n";
			$message .= sprintf($this->language->get('text_ciblog_post'), html_entity_decode($ciblog_post_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_commenter'), html_entity_decode($data['cibc_author'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_rating'), html_entity_decode($data['cibc_rating'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= $this->language->get('text_comment') . "\n";
			$message .= html_entity_decode($data['cibc_text'], ENT_QUOTES, 'UTF-8') . "\n\n";


			$mail = $this->ciblog->mail();

			$mail->setTo($admin_email);
			$mail->setReplyTo($data['cibc_email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_alert_email'));
			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}

		}

		return $ciblog_comment_id;
	}

	public function getAvgRatingOfCiBlogPosts($ciblog_post_id) {
		$sql = "SELECT AVG(r.rating) AS total FROM " . DB_PREFIX . "ciblog_comment r WHERE r.status = '1' AND r.ciblog_post_id='". (int)$ciblog_post_id ."'";
		return $this->db->query($sql)->row['total'];
	}

	public function getCiCommentsByCiBlogPostId($data=array()) {

		$sql = "SELECT r.*, p.ciblog_post_id, pd.name, p.image FROM " . DB_PREFIX . "ciblog_comment r LEFT JOIN " . DB_PREFIX . "ciblog_post p ON (r.ciblog_post_id = p.ciblog_post_id) LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (p.ciblog_post_id = pd.ciblog_post_id) WHERE p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if(!empty($data['ciblog_post_id'])) {
			$sql .= " AND p.ciblog_post_id = '" . (int)$data['ciblog_post_id'] . "' ";
		}

		$sort_data = array(
			'r.date_added',
			'r.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalCiCommentsByCiBlogPostId($data=array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_comment r LEFT JOIN " . DB_PREFIX . "ciblog_post p ON (r.ciblog_post_id = p.ciblog_post_id) LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (p.ciblog_post_id = pd.ciblog_post_id) WHERE p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if(!empty($data['ciblog_post_id'])) {
			$sql .= " AND p.ciblog_post_id = '" . (int)$data['ciblog_post_id'] . "'";
		}


		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}