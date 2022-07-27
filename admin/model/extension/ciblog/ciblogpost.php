<?php
class ModelExtensionCiBlogCiBlogPost extends Model {
	public function addCiBlogPost($data) {
		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_available']) || (isset($data['date_available']) && $data['date_available'] == '0000-00-00 00:00:00') ) {
			$data['date_available'] = date('Y-m-d');
		}



		$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post SET status = '" . (int)$data['status'] . "', ciblog_author_id = '" . (int)$data['ciblog_author_id'] . "', add_video_url = '" . (int)$data['add_video_url'] . "', video_url = '" . $this->db->escape($data['video_url']) . "', sort_order = '" . (int)$data['sort_order'] . "', allow_comment = '" . (int)$data['allow_comment'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "', date_available = '" . $this->db->escape($data['date_available']) . "'");

		$ciblog_post_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "ciblog_post SET image = '" . $this->db->escape($data['image']) . "' WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		}

		foreach ($data['ciblog_post_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_description SET ciblog_post_id = '" . (int)$ciblog_post_id . "', language_id = '" . (int)$language_id . "', image_title = '" . $this->db->escape($value['image_title']) . "', image_alt = '" . $this->db->escape($value['image_alt']) . "', name = '" . $this->db->escape($value['name']) . "', small_description = '" . $this->db->escape($value['small_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['ciblog_post_store'])) {
			foreach ($data['ciblog_post_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_to_store SET ciblog_post_id = '" . (int)$ciblog_post_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['ciblog_post_image'])) {
			foreach ($data['ciblog_post_image'] as $ciblog_post_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_image SET ciblog_post_id = '" . (int)$ciblog_post_id . "', image = '" . $this->db->escape($ciblog_post_image['image']) . "', sort_order = '" . (int)$ciblog_post_image['sort_order'] . "'");

				$ciblog_post_image_id = $this->db->getLastId();

				foreach ($ciblog_post_image['info'] as $language_id => $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_image_info SET ciblog_post_image_id = '" . (int)$ciblog_post_image_id . "', ciblog_post_id = '" . (int)$ciblog_post_id . "', language_id = '" . (int)$language_id . "', alt = '" . $this->db->escape($value['alt']) . "', `title` = '" . $this->db->escape($value['title']) . "'");
				}
			}
		}

		if (isset($data['ciblog_post_category'])) {
			foreach ($data['ciblog_post_category'] as $ciblog_category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_to_ciblog_category SET ciblog_post_id = '" . (int)$ciblog_post_id . "', ciblog_category_id = '" . (int)$ciblog_category_id . "'");
			}
		}

		if (isset($data['ciblog_post_related'])) {
			foreach ($data['ciblog_post_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_related SET ciblog_post_id = '" . (int)$ciblog_post_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related WHERE ciblog_post_id = '" . (int)$related_id . "' AND related_id = '" . (int)$ciblog_post_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_related SET ciblog_post_id = '" . (int)$related_id . "', related_id = '" . (int)$ciblog_post_id . "'");
			}
		}

		if (isset($data['ciblog_post_related_product'])) {
			foreach ($data['ciblog_post_related_product'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related_product WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_related_product SET ciblog_post_id = '" . (int)$ciblog_post_id . "', related_id = '" . (int)$related_id . "'");
			}
		}

		if (isset($data['ciblog_post_layout'])) {
			foreach ($data['ciblog_post_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_to_layout SET ciblog_post_id = '" . (int)$ciblog_post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if(VERSION <= '2.3.0.2') {
			if ($data['keyword']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ciblog_post_id=" . (int)$ciblog_post_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
		} else {
			if (isset($data['keyword'])) {
				foreach ($data['keyword'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'ciblog_post_id=" . (int)$ciblog_post_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
		}

		return $ciblog_post_id;
	}

	public function editCiBlogPost($ciblog_post_id, $data) {
		if(empty($data['date_added']) || (isset($data['date_added']) && $data['date_added'] == '0000-00-00 00:00:00') ) {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_modified']) || (isset($data['date_modified']) && $data['date_modified'] == '0000-00-00 00:00:00') ) {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		if(empty($data['date_available']) || (isset($data['date_available']) && $data['date_available'] == '0000-00-00 00:00:00') ) {
			$data['date_available'] = date('Y-m-d');
		}

		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_post SET status = '" . (int)$data['status'] . "', ciblog_author_id = '" . (int)$data['ciblog_author_id'] . "', add_video_url = '" . (int)$data['add_video_url'] . "', video_url = '" . $this->db->escape($data['video_url']) . "', sort_order = '" . (int)$data['sort_order'] . "', allow_comment = '" . (int)$data['allow_comment'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = '" . $this->db->escape($data['date_modified']) . "', date_available = '" . $this->db->escape($data['date_available']) . "' WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "ciblog_post SET image = '" . $this->db->escape($data['image']) . "' WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_description WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		foreach ($data['ciblog_post_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_description SET ciblog_post_id = '" . (int)$ciblog_post_id . "', language_id = '" . (int)$language_id . "', image_title = '" . $this->db->escape($value['image_title']) . "', image_alt = '" . $this->db->escape($value['image_alt']) . "', name = '" . $this->db->escape($value['name']) . "', small_description = '" . $this->db->escape($value['small_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_to_store WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		if (isset($data['ciblog_post_store'])) {
			foreach ($data['ciblog_post_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_to_store SET ciblog_post_id = '" . (int)$ciblog_post_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_image WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_image_info WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		if (isset($data['ciblog_post_image'])) {
			foreach ($data['ciblog_post_image'] as $ciblog_post_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_image SET ciblog_post_id = '" . (int)$ciblog_post_id . "', image = '" . $this->db->escape($ciblog_post_image['image']) . "', sort_order = '" . (int)$ciblog_post_image['sort_order'] . "'");

				$ciblog_post_image_id = $this->db->getLastId();

				foreach ($ciblog_post_image['info'] as $language_id => $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_image_info SET ciblog_post_image_id = '" . (int)$ciblog_post_image_id . "', ciblog_post_id = '" . (int)$ciblog_post_id . "', language_id = '" . (int)$language_id . "', alt = '" . $this->db->escape($value['alt']) . "', `title` = '" . $this->db->escape($value['title']) . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_to_ciblog_category WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		if (isset($data['ciblog_post_category'])) {
			foreach ($data['ciblog_post_category'] as $ciblog_category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_to_ciblog_category SET ciblog_post_id = '" . (int)$ciblog_post_id . "', ciblog_category_id = '" . (int)$ciblog_category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related WHERE related_id = '" . (int)$ciblog_post_id . "'");

		if (isset($data['ciblog_post_related'])) {
			foreach ($data['ciblog_post_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_related SET ciblog_post_id = '" . (int)$ciblog_post_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related WHERE ciblog_post_id = '" . (int)$related_id . "' AND related_id = '" . (int)$ciblog_post_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_related SET ciblog_post_id = '" . (int)$related_id . "', related_id = '" . (int)$ciblog_post_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related_product WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		if (isset($data['ciblog_post_related_product'])) {
			foreach ($data['ciblog_post_related_product'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related_product WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_related_product SET ciblog_post_id = '" . (int)$ciblog_post_id . "', related_id = '" . (int)$related_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_to_layout WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		if (isset($data['ciblog_post_layout'])) {
			foreach ($data['ciblog_post_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_to_layout SET ciblog_post_id = '" . (int)$ciblog_post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if(VERSION <= '2.3.0.2') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ciblog_post_id=" . (int)$ciblog_post_id . "'");

			if ($data['keyword']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ciblog_post_id=" . (int)$ciblog_post_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'ciblog_post_id=" . (int)$ciblog_post_id . "'");
			if (isset($data['keyword'])) {
				foreach ($data['keyword']as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'ciblog_post_id=" . (int)$ciblog_post_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
		}
	}

	public function copyCiBlogPost($ciblog_post_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "ciblog_post p WHERE p.ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['ciblog_post_description'] = $this->getCiBlogPostDescriptions($ciblog_post_id);
			$data['ciblog_post_image'] = $this->getCiBlogPostImages($ciblog_post_id);
			$data['ciblog_post_related'] = $this->getCiBlogPostRelated($ciblog_post_id);
			$data['ciblog_post_related_product'] = $this->getCiBlogPostRelatedProducts($ciblog_post_id);
			$data['ciblog_post_category'] = $this->getCiBlogPostCategories($ciblog_post_id);
			$data['ciblog_post_layout'] = $this->getCiBlogPostLayouts($ciblog_post_id);
			$data['ciblog_post_store'] = $this->getCiBlogPostStores($ciblog_post_id);

			$this->addCiBlogPost($data);
		}
	}

	public function deleteCiBlogPost($ciblog_post_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_description WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_image WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_image_info WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_related WHERE related_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_to_ciblog_category WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_to_layout WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_post_to_store WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ciblog_comment WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		if(VERSION <= '2.3.0.2') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ciblog_post_id=" . (int)$ciblog_post_id . "'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'ciblog_post_id=" . (int)$ciblog_post_id . "'");
		}
	}

	public function getCiBlogPost($ciblog_post_id) {
		$sql = "SELECT DISTINCT *";

		if(VERSION <= '2.3.0.2') {
			$sql .= " , (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'ciblog_post_id=" . (int)$ciblog_post_id . "') AS keyword ";
		}

		$sql .= " FROM " . DB_PREFIX . "ciblog_post p LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (p.ciblog_post_id = pd.ciblog_post_id) WHERE p.ciblog_post_id = '" . (int)$ciblog_post_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$query = $this->db->query($sql);

		return $query->row;
	}

	/*This function ues only on opencart version 3x*/
	public function getCiBlogPostSeoUrls($ciblog_post_id) {
		$cicategory_seo_url_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'ciblog_post_id=" . (int)$ciblog_post_id . "'");

		foreach ($query->rows as $result) {
			$cicategory_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $cicategory_seo_url_data;
	}

	public function getCiBlogPosts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ciblog_post p LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (p.ciblog_post_id = pd.ciblog_post_id) ";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_author'])) {
			$sql .= " AND p.ciblog_author_id IN (SELECT ciblog_author_id FROM " . DB_PREFIX . "ciauthor cia WHERE cia.name LIKE '" . $this->db->escape($data['filter_author']) . "%') ";
		}

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

		$sql .= " GROUP BY p.ciblog_post_id";

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

	public function getCiBlogPostsByCategoryId($ciblog_category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post p LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (p.ciblog_post_id = pd.ciblog_post_id) LEFT JOIN " . DB_PREFIX . "ciblog_post_to_ciblog_category p2c ON (p.ciblog_post_id = p2c.ciblog_post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.ciblog_category_id = '" . (int)$ciblog_category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getCiBlogPostDescriptions($ciblog_post_id) {
		$ciblog_post_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_description WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		foreach ($query->rows as $result) {
			$ciblog_post_description_data[$result['language_id']] = $result;
		}

		return $ciblog_post_description_data;
	}

	public function getCiBlogPostCategories($ciblog_post_id) {
		$ciblog_post_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_to_ciblog_category WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
		foreach ($query->rows as $result) {
			$ciblog_post_category_data[] = $result['ciblog_category_id'];
		}

		return $ciblog_post_category_data;
	}

	public function getCiBlogPostImages($ciblog_post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_image WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "' ORDER BY sort_order ASC");

		foreach ($query->rows as &$value) {

			$value['info'] = array();
			$query_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_image_info WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "' AND ciblog_post_image_id = '" . (int)$value['ciblog_post_image_id'] . "'");
			foreach ($query_info->rows as $value_info) {
				$value['info'][$value_info['language_id']] = $value_info;
			}
		}

		return $query->rows;
	}

	public function getCiBlogPostStores($ciblog_post_id) {
		$ciblog_post_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_to_store WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		foreach ($query->rows as $result) {
			$ciblog_post_store_data[] = $result['store_id'];
		}

		return $ciblog_post_store_data;
	}

	public function getCiBlogPostLayouts($ciblog_post_id) {
		$ciblog_post_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_to_layout WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		foreach ($query->rows as $result) {
			$ciblog_post_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $ciblog_post_layout_data;
	}

	public function getCiBlogPostRelated($ciblog_post_id) {
		$ciblog_post_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_related WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		foreach ($query->rows as $result) {
			$ciblog_post_related_data[] = $result['related_id'];
		}

		return $ciblog_post_related_data;
	}

	public function getCiBlogPostRelatedProducts($ciblog_post_id) {
		$ciblog_post_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_related_product WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		foreach ($query->rows as $result) {
			$ciblog_post_related_data[] = $result['related_id'];
		}

		return $ciblog_post_related_data;
	}

	public function getTotalCiBlogPosts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.ciblog_post_id) AS total FROM " . DB_PREFIX . "ciblog_post p LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (p.ciblog_post_id = pd.ciblog_post_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_author'])) {
			$sql .= " AND p.ciblog_author_id IN (SELECT ciblog_author_id FROM " . DB_PREFIX . "ciauthor cia WHERE cia.name LIKE '" . $this->db->escape($data['filter_author']) . "%') ";
		}

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

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalCiBlogPostsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_post_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}