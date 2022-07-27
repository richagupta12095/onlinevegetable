<?php
class ModelExtensionCiblogCiComment extends Model {
	public function stripTag(&$s) {

		do {
			$s = htmlentities($s, ENT_QUOTES, 'UTF-8');

			$s = str_replace('&amp;', '&', $s);

			$s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');

		} while (strpos($s, '&amp;') !== false);


		$s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');

		$s = strip_tags($s);


	}

	public function addCiComment($data) {

		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		$data['store_id'] = $this->config->get('config_store_id');
		$data['language_id'] = $this->config->get('config_language_id');

		$this->stripTag($data['author']);
		$this->stripTag($data['text']);
		$this->stripTag($data['email']);

		$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_comment SET author = '" . $this->db->escape($data['author']) . "', email = '" . $this->db->escape($data['email']) . "', ciblog_post_id = '" . (int)$data['ciblog_post_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', language_id = '" . (int)$data['language_id'] . "', rating = '" . (int)$data['rating'] . "', store_id = '" . (int)$data['store_id'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "'");

		$ciblog_comment_id = $this->db->getLastId();

		return $ciblog_comment_id;
	}

	public function editCiComment($ciblog_comment_id, $data) {
		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		$data['store_id'] = $this->config->get('config_store_id');
		$data['language_id'] = $this->config->get('config_language_id');

		$this->stripTag($data['author']);
		$this->stripTag($data['text']);
		$this->stripTag($data['email']);

		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_comment SET author = '" . $this->db->escape($data['author']) . "', email = '" . $this->db->escape($data['email']) . "', ciblog_post_id = '" . (int)$data['ciblog_post_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', language_id = '" . (int)$data['language_id'] . "', rating = '" . (int)$data['rating'] . "', store_id = '" . (int)$data['store_id'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "' WHERE ciblog_comment_id = '" . (int)$ciblog_comment_id . "'");
	}

	public function deleteCiComment($ciblog_comment_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_comment WHERE ciblog_comment_id = '" . (int)$ciblog_comment_id . "'");
	}

	public function getCiComment($ciblog_comment_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "ciblog_post_description pd WHERE pd.ciblog_post_id = r.ciblog_post_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS ciblog_post FROM " . DB_PREFIX . "ciblog_comment r WHERE r.ciblog_comment_id = '" . (int)$ciblog_comment_id . "'");

		return $query->row;
	}

	public function getCiComments($data = array()) {
		$sql = "SELECT r.*, pd.name as ciblog_post FROM " . DB_PREFIX . "ciblog_comment r LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (r.ciblog_post_id = pd.ciblog_post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_ciblog_post_id'])) {
			$sql .= " AND r.ciblog_post_id = '" . (int)$data['filter_ciblog_post_id'] . "'";
		}

		if (!empty($data['filter_ciblog_post'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_ciblog_post']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (!empty($data['filter_title'])) {
			$sql .= " AND r.`title` LIKE '" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND r.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
		}
		if (isset($data['filter_rating']) && !is_null($data['filter_rating'])) {
			$sql .= " AND r.rating = '" . (int)$data['filter_rating'] . "'";
		}
		if (isset($data['filter_language_id']) && !is_null($data['filter_language_id'])) {
			$sql .= " AND r.language_id = '" . (int)$data['filter_language_id'] . "'";
		}
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$sql .= " AND r.store_id = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		$sort_data = array(
			'pd.name',
			'r.author',
			'r.email',
			'r.language_id',
			'r.store_id',
			'r.rating',
			'r.status',
			'r.date_added',
			'r.date_modified',
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

	public function getTotalCiComments($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_comment r LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (r.ciblog_post_id = pd.ciblog_post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_ciblog_post_id'])) {
			$sql .= " AND r.ciblog_post_id = '" . (int)$data['filter_ciblog_post_id'] . "'";
		}

		if (!empty($data['filter_ciblog_post'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_ciblog_post']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (!empty($data['filter_title'])) {
			$sql .= " AND r.`title` LIKE '" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND r.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
		}
		if (isset($data['filter_rating']) && !is_null($data['filter_rating'])) {
			$sql .= " AND r.rating = '" . (int)$data['filter_rating'] . "'";
		}
		if (isset($data['filter_language_id']) && !is_null($data['filter_language_id'])) {
			$sql .= " AND r.language_id = '" . (int)$data['filter_language_id'] . "'";
		}
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$sql .= " AND r.store_id = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalCiCommentsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_comment WHERE status = '0'");

		return $query->row['total'];
	}
}