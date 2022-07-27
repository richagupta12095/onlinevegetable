<?php 
class ModelExtensionPaymentPayu extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('extension/payment/pumcp');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_payu_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('payment_payu_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_payu_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true; 
		} else {
			$status = false;
		}

		if (strtoupper($this->session->data['currency']) != $this->config->get('config_currency')) {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'payu',
        		'title'      => 'Credit Card / Debit Card / Net Banking (PayUmoney)',
				'terms'      => '',
				'sort_order' => $this->config->get('payment_payu_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
	
	
	
}
?>