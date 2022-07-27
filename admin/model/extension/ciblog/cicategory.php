<?php
class ModelExtensionCiBlogCiCategory extends Model {
	public function addCiCategory($data) {
		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_category SET parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "'");

		$ciblog_category_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "ciblog_category SET image = '" . $this->db->escape($data['image']) . "' WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");
		}

		foreach ($data['cicategory_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_category_description SET ciblog_category_id = '" . (int)$ciblog_category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', image_alt = '" . $this->db->escape($value['image_alt']) . "', image_title = '" . $this->db->escape($value['image_title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ciblog_category_path` WHERE ciblog_category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "ciblog_category_path` SET `ciblog_category_id` = '" . (int)$ciblog_category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "ciblog_category_path` SET `ciblog_category_id` = '" . (int)$ciblog_category_id . "', `path_id` = '" . (int)$ciblog_category_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['cicategory_store'])) {
			foreach ($data['cicategory_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_category_to_store SET ciblog_category_id = '" . (int)$ciblog_category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Set which layout to use with this category
		if (isset($data['cicategory_layout'])) {
			foreach ($data['cicategory_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_category_to_layout SET ciblog_category_id = '" . (int)$ciblog_category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}
		if(VERSION <= '2.3.0.2') {
			if (isset($data['keyword'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ciblog_category_id=" . (int)$ciblog_category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
		} else {
			if (isset($data['keyword'])) {
				foreach ($data['keyword'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'ciblog_category_id=" . (int)$ciblog_category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
		}

		return $ciblog_category_id;
	}

	public function editCiCategory($ciblog_category_id, $data) {
		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_category SET parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "' WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "ciblog_category SET image = '" . $this->db->escape($data['image']) . "' WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_category_description WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		foreach ($data['cicategory_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_category_description SET ciblog_category_id = '" . (int)$ciblog_category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', image_alt = '" . $this->db->escape($value['image_alt']) . "', image_title = '" . $this->db->escape($value['image_title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ciblog_category_path` WHERE path_id = '" . (int)$ciblog_category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "ciblog_category_path` WHERE ciblog_category_id = '" . (int)$category_path['ciblog_category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ciblog_category_path` WHERE ciblog_category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ciblog_category_path` WHERE ciblog_category_id = '" . (int)$category_path['ciblog_category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "ciblog_category_path` SET ciblog_category_id = '" . (int)$category_path['ciblog_category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "ciblog_category_path` WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ciblog_category_path` WHERE ciblog_category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "ciblog_category_path` SET ciblog_category_id = '" . (int)$ciblog_category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "ciblog_category_path` SET ciblog_category_id = '" . (int)$ciblog_category_id . "', `path_id` = '" . (int)$ciblog_category_id . "', level = '" . (int)$level . "'");
		}


		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_category_to_store WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		if (isset($data['cicategory_store'])) {
			foreach ($data['cicategory_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_category_to_store SET ciblog_category_id = '" . (int)$ciblog_category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_category_to_layout WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		if (isset($data['cicategory_layout'])) {
			foreach ($data['cicategory_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_category_to_layout SET ciblog_category_id = '" . (int)$ciblog_category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if(VERSION <= '2.3.0.2') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ciblog_category_id=" . (int)$ciblog_category_id . "'");
			if ($data['keyword']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ciblog_category_id=" . (int)$ciblog_category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'ciblog_category_id=" . (int)$ciblog_category_id . "'");
			if (isset($data['keyword'])) {
				foreach ($data['keyword']as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'ciblog_category_id=" . (int)$ciblog_category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
		}
	}

	public function deleteCiCategory($ciblog_category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_category_path WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_category_path WHERE path_id = '" . (int)$ciblog_category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCiCategory($result['ciblog_category_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_category WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_category_description WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_category_to_store WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_category_to_layout WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_to_ciblog_category WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		if(VERSION <= '2.3.0.2') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ciblog_category_id=" . (int)$ciblog_category_id . "'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'ciblog_category_id=" . (int)$ciblog_category_id . "'");
		}
	}

	public function repairCiCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_category WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "ciblog_category_path` WHERE ciblog_category_id = '" . (int)$category['ciblog_category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ciblog_category_path` WHERE ciblog_category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "ciblog_category_path` SET ciblog_category_id = '" . (int)$category['ciblog_category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "ciblog_category_path` SET ciblog_category_id = '" . (int)$category['ciblog_category_id'] . "', `path_id` = '" . (int)$category['ciblog_category_id'] . "', level = '" . (int)$level . "'");

			$this->repairCiCategories($category['ciblog_category_id']);
		}
	}

	public function getCiCategory($ciblog_category_id) {
		$sql = "SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "ciblog_category_path cp LEFT JOIN " . DB_PREFIX . "ciblog_category_description cd1 ON (cp.path_id = cd1.ciblog_category_id AND cp.ciblog_category_id != cp.path_id) WHERE cp.ciblog_category_id = c.ciblog_category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.ciblog_category_id) AS ciblogpath ";
		if(VERSION <= '2.3.0.2') {
			$sql .= " , (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'ciblog_category_id=" . (int)$ciblog_category_id . "') AS keyword";
		}
		$sql .= " FROM " . DB_PREFIX . "ciblog_category c LEFT JOIN " . DB_PREFIX . "ciblog_category_description cd2 ON (c.ciblog_category_id = cd2.ciblog_category_id) WHERE c.ciblog_category_id = '" . (int)$ciblog_category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$query = $this->db->query($sql);

		return $query->row;
	}

	/*This function ues only on opencart version 3x*/
	public function getCiCategorySeoUrls($ciblog_category_id) {
		$cicategory_seo_url_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'ciblog_category_id=" . (int)$ciblog_category_id . "'");

		foreach ($query->rows as $result) {
			$cicategory_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $cicategory_seo_url_data;
	}

	public function getCiCategories($data = array()) {
		$sql = "SELECT cp.ciblog_category_id AS ciblog_category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.* FROM " . DB_PREFIX . "ciblog_category_path cp LEFT JOIN " . DB_PREFIX . "ciblog_category c1 ON (cp.ciblog_category_id = c1.ciblog_category_id) LEFT JOIN " . DB_PREFIX . "ciblog_category c2 ON (cp.path_id = c2.ciblog_category_id) LEFT JOIN " . DB_PREFIX . "ciblog_category_description cd1 ON (cp.path_id = cd1.ciblog_category_id) LEFT JOIN " . DB_PREFIX . "ciblog_category_description cd2 ON (cp.ciblog_category_id = cd2.ciblog_category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(c1.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(c1.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND c1.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY cp.ciblog_category_id";

		$sort_data = array(
			'name',
			'c1.date_added',
			'c1.date_modified',
			'c1.status',
			'c1.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c1.sort_order";
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

	public function getCiCategoryDescriptions($ciblog_category_id) {
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_category_description WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = $result;
		}

		return $category_description_data;
	}

	public function getCiCategoryPath($ciblog_category_id) {
		$query = $this->db->query("SELECT ciblog_category_id, path_id, level FROM " . DB_PREFIX . "ciblog_category_path WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		return $query->rows;
	}


	public function getCiCategoryStores($ciblog_category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_category_to_store WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	public function getCiCategoryLayouts($ciblog_category_id) {
		$category_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_category_to_layout WHERE ciblog_category_id = '" . (int)$ciblog_category_id . "'");

		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	public function getTotalCiCategories($data = array()) {

		$sql = "SELECT COUNT(cp.ciblog_category_id) AS total FROM " . DB_PREFIX . "ciblog_category_path cp LEFT JOIN " . DB_PREFIX . "ciblog_category c1 ON (cp.ciblog_category_id = c1.ciblog_category_id) LEFT JOIN " . DB_PREFIX . "ciblog_category c2 ON (cp.path_id = c2.ciblog_category_id) LEFT JOIN " . DB_PREFIX . "ciblog_category_description cd1 ON (cp.path_id = cd1.ciblog_category_id) LEFT JOIN " . DB_PREFIX . "ciblog_category_description cd2 ON (cp.ciblog_category_id = cd2.ciblog_category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
// "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_category"

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(c1.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(c1.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND c1.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalCiCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
