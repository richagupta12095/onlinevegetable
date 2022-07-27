<?php
class Sms {

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');
		$this->currency = $registry->get('currency');
	}

	public function sendSMS($telephones = array(), $message = '') {
		if ($this->config->get('module_opc_sms_status') && $this->config->get('module_opc_sms_authkey')) {
			$curl = curl_init();

			$parameters = array(
				'sender' => $this->config->get('module_opc_sms_sender'),
				'route' => 4,
				'country' => $this->config->get('module_opc_sms_country'),
				'sms' => array(array(
					'message' => $message,
					'to' => $telephones,
				)),
			);

			$headers = array(
				"authkey: " . $this->config->get('module_opc_sms_authkey'),
				"content-type: application/json"
			);

			curl_setopt_array($curl, array(
				CURLOPT_URL => "http://api.msg91.com/api/v2/sendsms",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => json_encode($parameters),
				CURLOPT_HTTPHEADER => $headers,
			));

			$response = json_decode(curl_exec($curl));

			$err = curl_error($curl);

			curl_close($curl);

			if (!$err) {
				if (isset($response->type)) {
					foreach ($telephones as $value) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "opc_sms SET mobile = '" . $value . "', message = '" . $message . "', response = '" . $response->type . "', date_added = NOW()");
					}
				}
			}
		}
	}

	public function resendSMS($ids = array()) {
		if ($this->config->get('module_opc_sms_status') && $this->config->get('module_opc_sms_authkey') && $ids) {
			foreach ($ids as $id) {
				$query = $this->db->query("SELECT mobile, message FROM " . DB_PREFIX . "opc_sms WHERE id = " . (int)$id)->row;

				if (isset($query['mobile']) && $query['mobile'] && isset($query['message']) && $query['message']) {
					$this->sendSMS(array($query['mobile']), $query['message']);
				}
			}
		}
	}

	public function sendToCustomer($customer_id = 0) {
		if ($this->config->get('module_opc_sms_status') && $this->config->get('module_opc_sms_customer_add') && $customer_id) {

			$query = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) AS name, telephone FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$customer_id)->row;

			$template = $this->db->query("SELECT message FROM " . DB_PREFIX . "opc_sms_template WHERE id = " . (int)$this->config->get('module_opc_sms_customer_add'))->row;

			if (isset($query['telephone']) && $query['telephone'] && isset($template['message']) && $template['message']) {
				$template['message'] = str_replace('{{customer}}', $query['name'], $template['message']);

				$this->sendSMS(array($query['telephone'], $this->config->get('config_telephone')), $template['message']);
			}
	  }
	}

	public function sendOnOrder($order_id = 0) {
	  if ($this->config->get('module_opc_sms_status') && $this->config->get('module_opc_sms_order_add') && $order_id) {

			$query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'")->row;

			$template = $this->db->query("SELECT message FROM " . DB_PREFIX . "opc_sms_template WHERE id = " . (int)$this->config->get('module_opc_sms_order_add'))->row;

	    if (isset($query['telephone']) && $query['telephone'] && isset($template['message']) && $template['message']) {
	      $template['message'] = str_replace('{{customer}}', $query['firstname'] . ' ' . $query['lastname'], $template['message']);

				$text = 'Order ID: ' . $order_id . "\n";

				$text .= 'Order Date: ' . $query['date_added'] . "\n";

				$text .= 'Order Status: ' . $query['order_status'] . "\n";

				$text .= 'Order Total: ' . $this->currency->format($query['total'], $query['currency_code'], $query['currency_value']) . "\n";

				$text .= 'Product List \n';

				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

				foreach ($order_product_query->rows as $product) {
					$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $query['currency_code'], $query['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

					foreach ($order_option_query->rows as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
					}
				}

				$template['message'] = str_replace('{{order}}', $text, $template['message']);

	      $this->sendSMS(array($query['telephone'], $this->config->get('config_telephone')), $template['message']);
	    }
	  }
	}
}
