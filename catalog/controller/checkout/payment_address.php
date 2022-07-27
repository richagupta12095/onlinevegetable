<?php

class ControllerCheckoutPaymentAddress extends Controller {

	public function index() {

		$this->load->language('checkout/checkout');



		if (isset($this->session->data['payment_address']['address_id'])) {

			$data['address_id'] = $this->session->data['payment_address']['address_id'];

		} else {

			$data['address_id'] = $this->customer->getAddressId();

		}



		$this->load->model('account/address');



		$data['addresses'] = $this->model_account_address->getAddresses();



		if (isset($this->session->data['payment_address']['country_id'])) {

			$data['country_id'] = $this->session->data['payment_address']['country_id'];

		} else {

			$data['country_id'] = $this->config->get('config_country_id');

		}



		if (isset($this->session->data['payment_address']['zone_id'])) {

			$data['zone_id'] = $this->session->data['payment_address']['zone_id'];

		} else {

			$data['zone_id'] = '';

		}



		$this->load->model('localisation/country');



		$data['countries'] = $this->model_localisation_country->getCountries();



		// Custom Fields

		$data['custom_fields'] = array();

		

		$this->load->model('account/custom_field');



		$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));



		foreach ($custom_fields as $custom_field) {

			if ($custom_field['location'] == 'address') {

				$data['custom_fields'][] = $custom_field;

			}

		}



		if (isset($this->session->data['payment_address']['custom_field'])) {

			$data['payment_address_custom_field'] = $this->session->data['payment_address']['custom_field'];

		} else {

			$data['payment_address_custom_field'] = array();

		}



		$this->response->setOutput($this->load->view('checkout/payment_address', $data));

	}



	public function save() {

		$this->load->language('checkout/checkout');



		$json = array();



		// Validate if customer is logged in.

		if (!$this->customer->isLogged()) {

			$json['redirect'] = $this->url->link('checkout/checkout', '', true);

		}



		// Validate cart has products and has stock.

		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {

			$json['redirect'] = $this->url->link('checkout/cart');

		}



		// Validate minimum quantity requirements.

		$products = $this->cart->getProducts();



		foreach ($products as $product) {

			$product_total = 0;



			foreach ($products as $product_2) {

				if ($product_2['product_id'] == $product['product_id']) {

					$product_total += $product_2['quantity'];

				}

			}



			if ($product['minimum'] > $product_total) {

				$json['redirect'] = $this->url->link('checkout/cart');



				break;

			}

		}



		if (!$json) {

			$this->load->model('account/address');

							

			if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {

				if (empty($this->request->post['address_id'])) {

					$json['error']['warning'] = $this->language->get('error_address');

				} elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {

					$json['error']['warning'] = $this->language->get('error_address');

				}



				if (!$json) {

					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post['address_id']);



					unset($this->session->data['payment_method']);

					unset($this->session->data['payment_methods']);

				}

			}else if(isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'edit'){ 
				
				if ((utf8_strlen(trim($this->request->post['edit_firstname'])) < 1) || (utf8_strlen(trim($this->request->post['edit_firstname'])) > 32)) {
					$json['error']['edit_firstname'] = $this->language->get('error_firstname');
				}

				if ((utf8_strlen(trim($this->request->post['edit_lastname'])) < 1) || (utf8_strlen(trim($this->request->post['edit_lastname'])) > 32)) {
					$json['error']['edit_lastname'] = $this->language->get('error_lastname');
				}

				if ((utf8_strlen(trim($this->request->post['edit_address_1'])) < 3) || (utf8_strlen(trim($this->request->post['edit_address_1'])) > 128)) {
					$json['error']['edit_address_1'] = $this->language->get('error_address_1');
				}

				if ((utf8_strlen($this->request->post['edit_city_dropdown']) < 2) || (utf8_strlen($this->request->post['edit_city_dropdown']) > 32)) {
					$json['error']['edit_city_dropdown'] = $this->language->get('error_city');
				}

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($this->request->post['edit_country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['edit_postcode'])) < 2 || utf8_strlen(trim($this->request->post['edit_postcode'])) > 10)) {
					$json['error']['edit_postcode'] = $this->language->get('error_postcode');
				}

				if ($this->request->post['edit_country_id'] == '') {
					$json['error']['edit_country'] = $this->language->get('error_country');
				}

				if (!isset($this->request->post['edit_zone_id']) || $this->request->post['edit_zone_id'] == '' || !is_numeric($this->request->post['edit_zone_id'])) {
					$json['error']['edit_zone'] = $this->language->get('error_zone');
				}
				//print_r($json);exit;
				if (!$json) {
					//print_r($this->request->post);exit;
					$this->model_account_address->editAddressOnCheckout($this->request->post['edit_address_id'], $this->request->post);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}

			}  else {

				if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {

					$json['error']['firstname'] = $this->language->get('error_firstname');

				}



				if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {

					$json['error']['lastname'] = $this->language->get('error_lastname');

				}



				if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {

					$json['error']['address_1'] = $this->language->get('error_address_1');

				}



				if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {

					$json['error']['city'] = $this->language->get('error_city');

				}



				$this->load->model('localisation/country');



				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);



				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {

					$json['error']['postcode'] = $this->language->get('error_postcode');

				}



				if ($this->request->post['country_id'] == '') {

					$json['error']['country'] = $this->language->get('error_country');

				}



				if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {

					$json['error']['zone'] = $this->language->get('error_zone');

				}



				// Custom field validation

				$this->load->model('account/custom_field');



				$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));



				foreach ($custom_fields as $custom_field) {

					if ($custom_field['location'] == 'address') {

						if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {

							$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);

						} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {

							$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);

						}

					}

				}



				if (!$json) {

					$address_id = $this->model_account_address->addAddress($this->customer->getId(), $this->request->post);



					$this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);



					// If no default address ID set we use the last address

					if (!$this->customer->getAddressId()) {

						$this->load->model('account/customer');

						

						$this->model_account_customer->editAddressId($this->customer->getId(), $address_id);

					}



					unset($this->session->data['payment_method']);

					unset($this->session->data['payment_methods']);

				}

			}

		}



		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($json));

	}
	//By Ankur Mittal
	public function getPaymentAddress() { 
		$json = array();
		
		if (isset($this->request->get['address_id'])) {
			$address_id = $this->request->get['address_id'];
		} 

		$json['address_id'] = $address_id;
		$this->load->model('account/address');

		$json['addresses'] = $this->model_account_address->getAddress($address_id);
		$this->load->model('localisation/zone');
		$json['zone_id'] = $json['addresses']['zone_id'];
		$json['city_name'] = $json['addresses']['city'];
		
		$json['zones'] = $this->model_localisation_zone->getZonesByCountryId(99);

		if (isset($json['addresses']['zone_id']) && !empty($json['addresses']['zone_id'])) {
			$json['cities'] = $this->model_localisation_zone->getCitiesByZoneId($json['addresses']['zone_id']);
		} else {
			$json['cities'] = '';
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}