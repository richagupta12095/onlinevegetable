<?php

class ModelExtensionpaymentewalletpayment extends Model {

	public function getMethod($address, $total) {

		$this->load->language('payment/e_wallet_payment');



		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('e_wallet_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");



		if ($this->config->get('e_wallet_total') > 0 && $this->config->get('e_wallet_total') > $total || !$this->config->get('e_wallet_status')) {

			$status = false;

		} elseif (!$this->config->get('e_wallet_geo_zone_id')) {

			$status = true;

		} elseif ($query->num_rows) {

			$status = true;

		} else {

			$status = false;

		}

		 

		$method_data = array();

		if ($status) {

			$method_data = array(

				'code'       => 'e_wallet_payment',

				'title'      => $this->config->get('e_wallet_title'),

				'terms'      => '',

				'sort_order' => $this->config->get('payment_e_wallet_payment_sort_order')

			);

		}
		 
		return $method_data;

	}

}