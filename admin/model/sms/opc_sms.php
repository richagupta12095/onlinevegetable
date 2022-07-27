<?php
class ModelSmsOpcSms extends Model {
	public function createTable() {
    $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "opc_sms (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `mobile` varchar(5000) NOT NULL,
      `message` varchar(5000) NOT NULL,
			`response` varchar(5000),
			`date_added` datetime NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "opc_sms_template (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(5000) NOT NULL,
      `message` varchar(5000) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
	}

  public function dropTable() {
    $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "opc_sms");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "opc_sms_template");
	}

	public function getTelephonesByProductsOrdered($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT telephone FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		return $query->rows;
	}

	public function getTotalTelephonesByProductsOrdered($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT telephone FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

		return $query->row['telephone'];
	}

	public function getTotalSMS($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "opc_sms WHERE 1";

		$implode = array();

		if (!empty($data['filter_id'])) {
			$implode[] = "id = " . (int)$data['filter_id'];
		}

		if (!empty($data['filter_mobile'])) {
		  $implode[] = "mobile = '" . $data['filter_mobile'] . "'";
		}

		if (!empty($data['filter_message'])) {
		  $implode[] = "message LIKE '%" . $data['filter_message']  . "%'";
		}

		if (!empty($data['filter_response'])) {
		  $implode[] = "response = '" . $data['filter_response'] . "'";
		}

		if (!empty($data['filter_date'])) {
		  $implode[] = "date_added LIKE '%" . $data['filter_date']  . "%'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getSMS($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "opc_sms WHERE 1";

		$implode = array();

		if (!empty($data['filter_id'])) {
			$implode[] = "id = " . (int)$data['filter_id'];
		}

		if (!empty($data['filter_mobile'])) {
		  $implode[] = "mobile = '" . $data['filter_mobile'] . "'";
		}

		if (!empty($data['filter_message'])) {
		  $implode[] = "message LIKE '%" . $data['filter_message']  . "%'";
		}

		if (!empty($data['filter_response'])) {
		  $implode[] = "response = '" . $data['filter_response'] . "'";
		}

		if (!empty($data['filter_date'])) {
		  $implode[] = "date_added LIKE '%" . $data['filter_date']  . "%'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'id',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY id";
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

	public function deleteSMS($ids = '')	{
		if ($ids) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "opc_sms WHERE id IN (" . $ids . ")");
		}
	}

	public function getTotalTemplates($data) {
	  $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "opc_sms_template WHERE 1";

	  $implode = array();

	  if (!empty($data['filter_id'])) {
	    $implode[] = "id = " . (int)$data['filter_id'];
	  }

	  if (!empty($data['filter_name'])) {
	    $implode[] = "name = '" . $data['filter_name'] . "'";
	  }

	  if (!empty($data['filter_message'])) {
	    $implode[] = "message LIKE '%" . $data['filter_message']  . "%'";
	  }

	  if ($implode) {
	    $sql .= " AND " . implode(" AND ", $implode);
	  }

	  $query = $this->db->query($sql);

	  return $query->row['total'];
	}

	public function getTemplates($data) {
	  $sql = "SELECT * FROM " . DB_PREFIX . "opc_sms_template WHERE 1";

	  $implode = array();

	  if (!empty($data['filter_id'])) {
	    $implode[] = "id = " . (int)$data['filter_id'];
	  }

	  if (!empty($data['filter_name'])) {
	    $implode[] = "name = '" . $data['filter_name'] . "'";
	  }

	  if (!empty($data['filter_message'])) {
	    $implode[] = "message LIKE '%" . $data['filter_message']  . "%'";
	  }

	  if ($implode) {
	    $sql .= " AND " . implode(" AND ", $implode);
	  }

	  $sort_data = array(
	    'id',
	  );

	  if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
	    $sql .= " ORDER BY " . $data['sort'];
	  } else {
	    $sql .= " ORDER BY id";
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

	public function deleteTemplate($ids = '')	{
	  if ($ids) {
	    $this->db->query("DELETE FROM " . DB_PREFIX . "opc_sms_template WHERE id IN (" . $ids . ")");
	  }
	}

	public function editTemplate($data = array()) {
    if (isset($this->request->get['id']) && $this->request->get['id']) {
			$this->db->query("UPDATE " . DB_PREFIX . "opc_sms_template SET name = '" . $this->db->escape($data['name']) . "', message = '" . $this->db->escape($data['message']) . "' WHERE id = " . (int)$this->request->get['id']);
    } else {
    	$this->db->query("INSERT INTO " . DB_PREFIX . "opc_sms_template SET name = '" . $this->db->escape($data['name']) . "', message = '" . $this->db->escape($data['message']) . "'");
    }
	}
}
