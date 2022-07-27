<?php
class ModelExtensionCiblogCiSubscriber extends Model {
	public function addCiSubscriber($data) {
		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_subscriber SET email = '" . $this->db->escape($data['email']) . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "'");

		$ciblog_subscriber_id = $this->db->getLastId();

		return $ciblog_subscriber_id;
	}

	public function editCiSubscriber($ciblog_subscriber_id, $data) {
		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_subscriber SET  email = '" . $this->db->escape($data['email']) . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "' WHERE ciblog_subscriber_id = '" . (int)$ciblog_subscriber_id . "'");
	}

	public function deleteCiSubscriber($ciblog_subscriber_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_subscriber WHERE ciblog_subscriber_id = '" . (int)$ciblog_subscriber_id . "'");
	}

	public function getCiSubscriber($ciblog_subscriber_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "ciblog_subscriber r WHERE r.ciblog_subscriber_id = '" . (int)$ciblog_subscriber_id . "'");

		return $query->row;
	}

	public function getCiSubscribers($data = array()) {
		$sql = "SELECT r.* FROM " . DB_PREFIX . "ciblog_subscriber r WHERE r.ciblog_subscriber_id>0";

		if (!empty($data['filter_email'])) {
			$sql .= " AND r.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		$sort_data = array(
			'r.email',
			'r.status',
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

	public function getTotalCiSubscribers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_subscriber r WHERE r.ciblog_subscriber_id>0";

		if (!empty($data['filter_email'])) {
			$sql .= " AND r.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
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

	public function getTotalCiSubscribersAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_subscriber WHERE status = '0'");

		return $query->row['total'];
	}
}