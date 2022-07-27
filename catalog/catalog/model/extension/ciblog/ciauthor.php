<?php
class ModelExtensionCiBlogCiAuthor extends Model {
	public function getCiAuthor($ciblog_author_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_author m LEFT JOIN " . DB_PREFIX . "ciblog_author_description md ON (m.ciblog_author_id = md.ciblog_author_id) LEFT JOIN " . DB_PREFIX . "ciblog_author_to_store m2s ON (m.ciblog_author_id = m2s.ciblog_author_id) WHERE m.status = '1' AND m.ciblog_author_id = '" . (int)$ciblog_author_id . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row;
	}

	public function getCiAuthors($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "ciblog_author m LEFT JOIN " . DB_PREFIX . "ciblog_author_description md ON (m.ciblog_author_id = md.ciblog_author_id) LEFT JOIN " . DB_PREFIX . "ciblog_author_to_store m2s ON (m.ciblog_author_id = m2s.ciblog_author_id) WHERE m.status = '1' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
}