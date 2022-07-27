<?php
class ModelExtensionCiBlogCiBlogPost extends Model {
	public function isHearted($ciblog_post_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ciblog_post_heart WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "' ";
		if($this->customer->getId()) {
			$sql .= " AND customer_id='". (int)$this->customer->getId() ."' ";
		} else {
			$sql .= " AND session_id='". $this->db->escape($this->session->getId()) ."' ";

		}
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getCiBlogPostHearts($ciblog_post_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_post_heart h2o WHERE h2o.ciblog_post_id = '". (int)$ciblog_post_id ."' AND h2o.status=1 AND heart=1 GROUP BY h2o.ciblog_post_id");
		return $query->row ? $query->row['total'] : 0;
	}
	public function updateHeart($ciblog_post_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ciblog_post_heart SET customer_id='". (int)$this->customer->getId() ."', session_id='". $this->db->escape($this->session->getId()) ."', ciblog_post_id = '" . (int)$ciblog_post_id . "', heart=1, status=1");
		$ciblog_post_heart_id = $this->db->getLastId();
		return $ciblog_post_heart_id;
	}

	public function updateViewed($ciblog_post_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "ciblog_post SET viewed = (viewed + 1) WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");
	}

	public function getCiBlogPost($ciblog_post_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "ciblog_comment r1 WHERE r1.ciblog_post_id = p.ciblog_post_id AND r1.status = '1' GROUP BY r1.ciblog_post_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_comment r2 WHERE r2.ciblog_post_id = p.ciblog_post_id AND r2.status = '1' GROUP BY r2.ciblog_post_id) AS comments, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ciblog_post_heart h2o WHERE h2o.ciblog_post_id = p.ciblog_post_id AND h2o.status=1 AND heart=1 GROUP BY h2o.ciblog_post_id) AS heart, p.sort_order FROM " . DB_PREFIX . "ciblog_post p LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (p.ciblog_post_id = pd.ciblog_post_id) LEFT JOIN " . DB_PREFIX . "ciblog_post_to_store p2s ON (p.ciblog_post_id = p2s.ciblog_post_id) WHERE p.ciblog_post_id = '" . (int)$ciblog_post_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'ciblog_post_id'       => $query->row['ciblog_post_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'small_description'      => $query->row['small_description'],
				'ciblog_author_id'      => $query->row['ciblog_author_id'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'image'            => $query->row['image'],
				'image_title'            => $query->row['image_title'],
				'image_alt'            => $query->row['image_alt'],
				'date_available'   => $query->row['date_available'],
				'rating'           => round($query->row['rating']),
				'comments'          => $query->row['comments'] ? $query->row['comments'] : 0,
				'heart'          => $query->row['heart'] ? $query->row['heart'] : 0,
				'allow_comment'       => $query->row['allow_comment'],
				'add_video_url'       => $query->row['add_video_url'],
				'video_url'       => $query->row['video_url'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getCiBlogPosts($data = array()) {
		$sql = "SELECT p.ciblog_post_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "ciblog_comment r1 WHERE r1.ciblog_post_id = p.ciblog_post_id AND r1.status = '1' GROUP BY r1.ciblog_post_id) AS rating";

		if (!empty($data['filter_ciblog_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "ciblog_category_path cp LEFT JOIN " . DB_PREFIX . "ciblog_post_to_ciblog_category p2c ON (cp.ciblog_category_id = p2c.ciblog_category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "ciblog_post_to_ciblog_category p2c";
			}

			$sql .= " LEFT JOIN " . DB_PREFIX . "ciblog_post p ON (p2c.ciblog_post_id = p.ciblog_post_id)";

		} else {
			$sql .= " FROM " . DB_PREFIX . "ciblog_post p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (p.ciblog_post_id = pd.ciblog_post_id) LEFT JOIN " . DB_PREFIX . "ciblog_post_to_store p2s ON (p.ciblog_post_id = p2s.ciblog_post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_ciblog_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_ciblog_category_id'] . "'";
			} else {
				$sql .= " AND p2c.ciblog_category_id = '" . (int)$data['filter_ciblog_category_id'] . "'";
			}
		}

		if (!empty($data['filter_ciblog_post_id'])) {
			$ciblog_post_id = explode("','", (string)$data['filter_ciblog_post_id']);

			$sql .= " AND p.ciblog_post_id IN ('" . implode("','", explode(",", (string)$data['filter_ciblog_post_id'])) . "')";
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				// $sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_ciblog_author_id'])) {
			$sql .= " AND p.ciblog_author_id = '" . (int)$data['filter_ciblog_author_id'] . "'";
		}

		if (!empty($data['filter_date'])) {
			$sql .= " AND DATE_FORMAT(p.date_added, '%Y-%m-%d' ) = '" . $data['filter_date'] . "'";
		}

		$sql .= " GROUP BY p.ciblog_post_id";

		$sort_data = array(
			'pd.name',
			'rating',
			'p.viewed',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$ciblog_post_data = array();
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$ciblog_post_data[$result['ciblog_post_id']] = $this->getCiBlogPost($result['ciblog_post_id']);
		}

		return $ciblog_post_data;
	}

	public function getLatestCiBlogPosts($limit) {

		$query = $this->db->query("SELECT p.ciblog_post_id FROM " . DB_PREFIX . "ciblog_post p LEFT JOIN " . DB_PREFIX . "ciblog_post_to_store p2s ON (p.ciblog_post_id = p2s.ciblog_post_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) {
			$ciblog_post_data[$result['ciblog_post_id']] = $this->getCiBlogPost($result['ciblog_post_id']);
		}

		return $ciblog_post_data;
	}

	public function getCiBlogPostImages($ciblog_post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_image WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getCiBlogPostRelated($ciblog_post_id) {
		$ciblog_post_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_related pr LEFT JOIN " . DB_PREFIX . "ciblog_post p ON (pr.related_id = p.ciblog_post_id) LEFT JOIN " . DB_PREFIX . "ciblog_post_to_store p2s ON (p.ciblog_post_id = p2s.ciblog_post_id) WHERE pr.ciblog_post_id = '" . (int)$ciblog_post_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$blogpost = $this->getCiBlogPost($result['related_id']);
			if($blogpost) {
				$ciblog_post_data[$result['related_id']] = $blogpost;
			}
		}
		return $ciblog_post_data;
	}

	public function getCiBlogPostLayoutId($ciblog_post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_to_layout WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCiCategories($ciblog_post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_to_ciblog_category WHERE ciblog_post_id = '" . (int)$ciblog_post_id . "'");

		return $query->rows;
	}

	public function getTotalCiBlogPosts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.ciblog_post_id) AS total";

		if (!empty($data['filter_ciblog_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "ciblog_category_path cp LEFT JOIN " . DB_PREFIX . "ciblog_post_to_ciblog_category p2c ON (cp.ciblog_category_id = p2c.ciblog_category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "ciblog_post_to_ciblog_category p2c";
			}

			$sql .= " LEFT JOIN " . DB_PREFIX . "ciblog_post p ON (p2c.ciblog_post_id = p.ciblog_post_id)";

		} else {
			$sql .= " FROM " . DB_PREFIX . "ciblog_post p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "ciblog_post_description pd ON (p.ciblog_post_id = pd.ciblog_post_id) LEFT JOIN " . DB_PREFIX . "ciblog_post_to_store p2s ON (p.ciblog_post_id = p2s.ciblog_post_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_ciblog_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_ciblog_category_id'] . "'";
			} else {
				$sql .= " AND p2c.ciblog_category_id = '" . (int)$data['filter_ciblog_category_id'] . "'";
			}
		}

		if (!empty($data['filter_ciblog_post_id'])) {
			$sql .= " AND p.ciblog_post_id IN ('" . explode("','", (string)$data['filter_ciblog_post_id']) . "')";
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				// $sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_ciblog_author_id'])) {
			$sql .= " AND p.ciblog_author_id = '" . (int)$data['filter_ciblog_author_id'] . "'";
		}

		if (!empty($data['filter_date'])) {
			$sql .= " AND DATE_FORMAT(p.date_added, '%Y-%m-%d' ) = '" . $data['filter_date'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getCiBlogTopViewPosts() {
		$ciblog_post_data = array();
		$query = $this->db->query("SELECT p.ciblog_post_id FROM " . DB_PREFIX . "ciblog_post p LEFT JOIN " . DB_PREFIX . "ciblog_post_to_store p2s ON (p.ciblog_post_id = p2s.ciblog_post_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) {
			$ciblog_post_data[$result['ciblog_post_id']] = $this->getCiBlogPost($result['ciblog_post_id']);
		}
		return $ciblog_post_data;
	}

	public function getProductRelatedToCiBlog($ciblog_post_id) {
		$ciblog_post_data = array();
		$this->load->model('catalog/product');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_related_product pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.ciblog_post_id = '" . (int)$ciblog_post_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$ciblog_post_data[$result['related_id']] = $this->model_catalog_product->getProduct($result['related_id']);
		}
		return $ciblog_post_data;

	}
	public function getCiBlogRelatedToProduct($product_id) {
		$ciblog_post_data = array();
		$this->load->model('catalog/product');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ciblog_post_related_product pr LEFT JOIN " . DB_PREFIX . "ciblog_post p ON (pr.ciblog_post_id = p.ciblog_post_id) LEFT JOIN " . DB_PREFIX . "ciblog_post_to_store p2s ON (p.ciblog_post_id = p2s.ciblog_post_id) WHERE pr.related_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$ciblog_post_data[$result['ciblog_post_id']] = $this->getCiBlogPost($result['ciblog_post_id']);
		}
		return $ciblog_post_data;

	}
}
