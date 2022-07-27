<?php
class ModelExtensionCiBlogCiAuthor extends Model {
	public function addCiAuthor($data) {
		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_author SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "'");

		$ciblog_author_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "ciblog_author SET image = '" . $this->db->escape($data['image']) . "' WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");
		}

		foreach ($data['ciauthor_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_author_description SET ciblog_author_id = '" . (int)$ciblog_author_id . "', language_id = '" . (int)$language_id . "',name = '" . $this->db->escape($value['name']) . "', image_alt = '" . $this->db->escape($value['image_alt']) . "', image_title = '" . $this->db->escape($value['image_title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['ciauthor_store'])) {
			foreach ($data['ciauthor_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_author_to_store SET ciblog_author_id = '" . (int)$ciblog_author_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if(VERSION <= '2.3.0.2') {
			if (isset($data['keyword'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ciblog_author_id=" . (int)$ciblog_author_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
		} else {
			if (isset($data['keyword'])) {
				foreach ($data['keyword'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'ciblog_author_id=" . (int)$ciblog_author_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
		}

		return $ciblog_author_id;
	}

	public function editCiAuthor($ciblog_author_id, $data) {
		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_author SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "' WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "ciblog_author SET image = '" . $this->db->escape($data['image']) . "' WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_author_description WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");
		foreach ($data['ciauthor_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_author_description SET ciblog_author_id = '" . (int)$ciblog_author_id . "', language_id = '" . (int)$language_id . "',name = '" . $this->db->escape($value['name']) . "', image_alt = '" . $this->db->escape($value['image_alt']) . "', image_title = '" . $this->db->escape($value['image_title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_author_to_store WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");

		if (isset($data['ciauthor_store'])) {
			foreach ($data['ciauthor_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_author_to_store SET ciblog_author_id = '" . (int)$ciblog_author_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if(VERSION <= '2.3.0.2') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ciblog_author_id=" . (int)$ciblog_author_id . "'");
			if ($data['keyword']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ciblog_author_id=" . (int)$ciblog_author_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'ciblog_author_id=" . (int)$ciblog_author_id . "'");
			if (isset($data['keyword'])) {
				foreach ($data['keyword']as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'ciblog_author_id=" . (int)$ciblog_author_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
		}

	}

	public function deleteCiAuthor($ciblog_author_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_author WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_author_description WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_author_to_store WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");
		if(VERSION <= '2.3.0.2') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ciblog_author_id=" . (int)$ciblog_author_id . "'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'ciblog_author_id=" . (int)$ciblog_author_id . "'");
		}
	}

	public function getCiBlogAuthorDescriptions($ciblog_author_id) {
		$ciblog_author_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_author_description WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");

		foreach ($query->rows as $result) {
			$ciblog_author_description_data[$result['language_id']] = $result;
		}

		return $ciblog_author_description_data;
	}

	public function getCiAuthor($ciblog_author_id) {
		$sql = "SELECT DISTINCT * ";
		if(VERSION <= '2.3.0.2') {
			$sql .= " , (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'ciblog_author_id=" . (int)$ciblog_author_id . "') AS keyword";
		}

		$sql .=" FROM " . DB_PREFIX . "ciblog_author p LEFT JOIN " . DB_PREFIX . "ciblog_author_description pd ON (p.ciblog_author_id = pd.ciblog_author_id) WHERE p.ciblog_author_id = '" . (int)$ciblog_author_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	/*This function ues only on opencart version 3x*/
	public function getCiAuthorSeoUrls($ciblog_author_id) {
		$author_seo_url_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'ciblog_author_id=" . (int)$ciblog_author_id . "'");

		foreach ($query->rows as $result) {
			$author_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $author_seo_url_data;
	}

	public function getCiAuthors($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ciblog_author p LEFT JOIN " . DB_PREFIX . "ciblog_author_description pd ON (p.ciblog_author_id = pd.ciblog_author_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(p.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		$sort_data = array(
			'pd.name',
			'p.status',
			'p.sort_order',
			'p.date_added',
			'p.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

	public function getCiAuthorStores($ciblog_author_id) {
		$ciauthor_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_author_to_store WHERE ciblog_author_id = '" . (int)$ciblog_author_id . "'");

		foreach ($query->rows as $result) {
			$ciauthor_store_data[] = $result['store_id'];
		}

		return $ciauthor_store_data;
	}

	public function getTotalCiAuthors($data=array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_author p LEFT JOIN " . DB_PREFIX . "ciblog_author_description pd ON (p.ciblog_author_id = pd.ciblog_author_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(p.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		// "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_author"
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
