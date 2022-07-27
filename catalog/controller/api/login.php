<?php

class ControllerApiLogin extends Controller {

	public function index() {

		$this->load->language('api/login');



		$json = array();



		$this->load->model('account/api');



		// Login with API Key

		$api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['key']);



		if ($api_info) {

			// Check if IP is allowed

			$ip_data = array();

	

			$results = $this->model_account_api->getApiIps($api_info['api_id']);

	

			foreach ($results as $result) {

				$ip_data[] = trim($result['ip']);

			}

	

			if (!in_array($this->request->server['REMOTE_ADDR'], $ip_data)) {

				$json['error']['ip'] = sprintf($this->language->get('error_ip'), $this->request->server['REMOTE_ADDR']);

			}				

				

			if (!$json) {

				$json['success'] = $this->language->get('text_success');

				

				$session = new Session($this->config->get('session_engine'), $this->registry);

				$session->start();

				

				$this->model_account_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);

				

				$session->data['api_id'] = $api_info['api_id'];

				

				// Create Token

				$json['api_token'] = $session->getId();

			} else {

				$json['error']['key'] = $this->language->get('error_key');

			}

		}

		

		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($json));

	}

	public function getOtp()
	{
		if(!empty($this->request->post['mobile'])){
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://control.msg91.com/api/sendotp.php",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "authkey=325067Ae3HWB2Y5e8215f4P1&template_id=iavoca&mobile=%2B91".$this->request->post['mobile'],
			  CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache",
			    "content-type: application/x-www-form-urlencoded",
			    "postman-token: 4ee1c064-d86f-03c5-96cd-83f591793d93"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  	$json['status'] = 'error';
				$json['message'] = $err;
			} else {
			  $result = json_decode($response);
				$json = array();
				$json['status'] = $result->type;
				$json['message'] = $result->message;
			}			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			

		}
		
	}

	public function verifyOtp()
	{
		if(!empty($this->request->post['mobile']) && !empty($this->request->post['otp'])){
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.msg91.com/api/v5/otp/verify",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "authkey=325067Ae3HWB2Y5e8215f4P1&mobile=%2B91".$this->request->post['mobile']."&otp=".$this->request->post['otp'],
			  CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache",
			    "content-type: application/x-www-form-urlencoded",
			    "postman-token: 4ee1c064-d86f-03c5-96cd-83f591793d93"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);
			$json = array();
			if ($err) {
			  	$json['status'] = 'error';
				$json['message'] = $err;
			} else {
				$this->load->model('account/customer');
			  	$result 			= json_decode($response);
				$json 				= array();
				$json['status'] 	= $result->type;
				$json['message'] 	= $result->message;
				$data 		= $this->model_account_customer->getCustomerByPhone($this->request->post['mobile']);
				if(!empty($data)){
					$json['data'] = $data;
				}else{
					$json['data'] = (object)"";
				}			  
			}	
			}else{
				$json['status'] = 'error';
				$json['message'] = 'error in request';
			}			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));		
	}

	public function getCustomerDetails(){		
			if(!empty($this->request->post['mobile']) && !empty($this->request->post['otp'])){
				$curl = curl_init();
	
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://api.msg91.com/api/v5/otp/verify",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => "authkey=325067Ae3HWB2Y5e8215f4P1&mobile=%2B91".$this->request->post['mobile']."&otp=".$this->request->post['otp'],
				  CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
					"content-type: application/x-www-form-urlencoded",
					"postman-token: 4ee1c064-d86f-03c5-96cd-83f591793d93"
				  ),
				));
	
				$response = curl_exec($curl);
				$err = curl_error($curl);
	
				curl_close($curl);
				$json = array();
				if ($err) {
					  $json['status'] = 'error';
					$json['message'] = $err;
				} else {
					$this->load->model('account/customer');
					  $result 			= json_decode($response);
					$json 				= array();
					$json['status'] 	= $result->type;
					$json['message'] 	= $result->message;
					$data 		= $this->model_account_customer->getCustomerByPhone($this->request->post['mobile']);
					if(!empty($data)){
						$json['data'] = $data;
					}else{
						$json['data'] = (object)"";
					}			  
				}	
				}else{
					$json['status'] = 'error';
					$json['message'] = 'error in request';
				}			
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));		
	}

	public function register()
	{
		$this->load->model('account/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !empty($this->request->post)) {
			$data = array();
			$json = array();				
			$data['customer_group_id'] = 1;

			if (isset($this->request->post['firstname'])) {
				$data['firstname'] = $this->request->post['firstname'];
			} else {
				$data['firstname'] = '';
			}

			if (isset($this->request->post['lastname'])) {
				$data['lastname'] = $this->request->post['lastname'];
			} else {
				$data['lastname'] = '';
			}

			if (isset($this->request->post['email']) && !empty($this->request->post['email'])) {
				$data['email'] = $this->request->post['email'];
			} else {
				$data['email'] = '';
			}

			if (isset($this->request->post['telephone']) && !empty($this->request->post['telephone'])) {
				$data['telephone'] = $this->request->post['telephone'];
			} else {
				$data['telephone'] = '';
			}

			if (isset($this->request->post['password'])) {
				$data['password'] = $this->request->post['password'];
			} else {
				$data['password'] = uniqid(rand(), true);
			}			
			
			$email_customers = $this->model_account_customer->getTotalCustomersByEmail($data['email']);
			$phone_customers = $this->model_account_customer->getTotalCustomersByMobile($data['telephone']);

			if(!empty($email_customers) || !empty($phone_customers)){
				
				$json['status']  = 'error';
				$json['message'] = 'Email and Phone number is Already registered';

			}else if(!empty($data['telephone']) && !empty($data['email'])){

				$customer_id 		 = $this->model_account_customer->addCustomer($data);
				$json['status'] 	 = 'success';
				$json['message'] 	 = 'Customer Registered successfully';
				$data['customer_id'] =  $customer_id;
				$json['data'] 		 =  $data;

			}else{

				$json['status'] = 'error';
				$json['message'] = 'Email and Phone number should not be empty';

			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));				
			
		}
	}
	
	public function addAddress()
	{
		$this->load->model('account/address');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !empty($this->request->post) && !empty($this->request->post['customer_id'])) {
			$data = array();
			$json = array();				
			
			if (isset($this->request->post['customer_id'])) {
				$data['customer_id'] = $this->request->post['customer_id'];
			} else {
				$data['customer_id'] = '';
			}

			if (isset($this->request->post['firstname'])) {
				$data['firstname'] = $this->request->post['firstname'];
			} else {
				$data['firstname'] = '';
			}

			if (isset($this->request->post['lastname'])) {
				$data['lastname'] = $this->request->post['lastname'];
			} else {
				$data['lastname'] = '';
			}

			if (isset($this->request->post['address_1']) && !empty($this->request->post['address_1'])) {
				$data['address_1'] = $this->request->post['address_1'];
			} else {
				$data['address_1'] = '';
			}

			if (isset($this->request->post['address_2']) && !empty($this->request->post['address_2'])) {
				$data['address_2'] = $this->request->post['address_2'];
			} else {
				$data['address_2'] = '';
			}

			if (isset($this->request->post['postcode'])) {
				$data['postcode'] = $this->request->post['postcode'];
			} else {
				$data['postcode'] = '';
			}	

			if (isset($this->request->post['zone_id'])) {
				$data['zone_id'] = $this->request->post['zone_id'];
			} else {
				$data['zone_id'] = '';
			}

			if (isset($this->request->post['city'])) {
				$data['city'] = $this->request->post['city'];
			} else {
				$data['city'] = '';
			}
			
			if (isset($this->request->post['default'])) {
				$data['default'] = $this->request->post['default'];
			} else {
				$data['default'] = '';
			}		
			$data['country_id'] =  99;
			$data['company']  = ''; 
			if(!empty($data['customer_id']) && !empty($data['zone_id']) && !empty($data['address_1'])){
				$address_id = $this->model_account_address->addAddress($data['customer_id'],$data);
				$json['status'] = 'success';
				$json['message'] = 'Address Added successfully';
				$json['address_id'] = $address_id;
				$addresses 		= $this->model_account_address->getCustomerAddresses($data['customer_id'] );				
				foreach($addresses as $address){
					$addressesfin[] = 	$address;
				}
				$json['addresses'] =$addressesfin;
			}else{
				$json['status'] = 'error';
				$json['message'] = 'Address and Zone should not be empty';
			}				
			
		}else{ 
			$json['status'] = 'error';
			$json['message'] = 'Customer Id Should be Present.';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}

	public function editAddress()
	{
		$this->load->model('account/address');
		$this->load->model('account/api');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !empty($this->request->post) && !empty($this->request->post['customer_id'])) {
			$data = array();
			$json = array();

			if (isset($this->request->post['address_id'])) {
				$data['address_id'] = $this->request->post['address_id'];
			} else {
				$data['address_id'] = '';
			}


			if (isset($this->request->post['customer_id'])) {
				$data['customer_id'] = $this->request->post['customer_id'];
			} else {
				$data['customer_id'] = '';
			}

			if (isset($this->request->post['firstname'])) {
				$data['firstname'] = $this->request->post['firstname'];
			} else {
				$data['firstname'] = '';
			}

			if (isset($this->request->post['lastname'])) {
				$data['lastname'] = $this->request->post['lastname'];
			} else {
				$data['lastname'] = '';
			}

			if (isset($this->request->post['address_1']) && !empty($this->request->post['address_1'])) {
				$data['address_1'] = $this->request->post['address_1'];
			} else {
				$data['address_1'] = '';
			}

			if (isset($this->request->post['address_2']) && !empty($this->request->post['address_2'])) {
				$data['address_2'] = $this->request->post['address_2'];
			} else {
				$data['address_2'] = '';
			}

			if (isset($this->request->post['postcode'])) {
				$data['postcode'] = $this->request->post['postcode'];
			} else {
				$data['postcode'] = '';
			}	

			if (isset($this->request->post['company'])) {
				$data['company'] = $this->request->post['company'];
			} else {
				$data['company'] = '';
			}

			if (isset($this->request->post['zone_id'])) {
				$data['zone_id'] = $this->request->post['zone_id'];
			} else {
				$data['zone_id'] = '';
			}

			if (isset($this->request->post['city'])) {
				$data['city'] = $this->request->post['city'];
			} else {
				$data['city'] = '';
			}
			
			if (isset($this->request->post['default'])) {
				$data['default'] = $this->request->post['default'];
			} else {
				$data['default'] = '';
			}		
			$data['country_id'] =  99;
			
			if(!empty($data['customer_id']) && !empty($data['zone_id']) && !empty($data['address_1'])){
				$address_id = $this->model_account_api->editAddress($data['address_id'],$data);
				$json['status'] = 'success';
				$json['message'] = 'Address Updated successfully';
				$json['address_id'] = $data['address_id'];
				$addresses = $this->model_account_address->getCustomerAddresses($data['customer_id'] );
				$json['status'] = 'success';
				$json['message'] = 'Address Added successfully';
				foreach($addresses as $address){
					$addressesfin[] = 	$address;
				}
				$json['addresses'] =$addressesfin;
			}else{
				$json['status'] = 'error';
				$json['message'] = 'Address ID and Customer ID should not be empty';
			}	
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));	
			
		}
	}

	public function getAddresses()
	{
		$this->load->model('account/address');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !empty($this->request->post) && !empty($this->request->post['customer_id'])) {
			$data = array();
			$json = array();
			
			if (isset($this->request->post['customer_id'])) {
				$data['customer_id'] = $this->request->post['customer_id'];
			} else {
				$data['customer_id'] = '';
			}

			if(!empty($data['customer_id'])){
				$addresses = $this->model_account_address->getCustomerAddresses($data['customer_id'] );
				$json['status'] = 'success';
				$json['message'] = 'Address Added successfully';
				foreach($addresses as $address){
					$addressesfin[] = 	$address;
				}
				$json['addresses'] =$addressesfin;
			
			}else{
				$json['status'] = 'error';
				$json['message'] = 'Address and Zone Not found for this customer';
			}	
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));	
			
		}
	}

	public function deleteAddresses()
	{
		
		$this->load->model('api/order');
		$this->load->model('account/address');
		if (!empty($this->request->post['customer_id']) && !empty($this->request->post['address_id'])) {
			$data = array();
			$json = array();
			
			$this->model_api_order->deleteAddress($this->request->post['address_id'],$this->request->post['customer_id']);
			$json['status'] = 'success';
			$json['message'] = 'Address Deleted successfully';	
			$addresses = $this->model_account_address->getCustomerAddresses($this->request->post['customer_id']);
			$json['status'] = 'success';
			$json['message'] = 'Address Added successfully';
			foreach($addresses as $address){
				$addressesfin[] = 	$address;
			}
			$json['addresses'] =$addressesfin;
		}else{
			$json['status'] = 'error';
			$json['message'] = 'Address id or Customer Id Not Matched';
		}
		$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));	
	}
	public function getCustomerOrders() {
		
		$this->load->language('account/order');

		$data['orders'] = array();

		$this->load->model('account/order');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !empty($this->request->post) && !empty($this->request->post['customer_id'])) {
			
			$results = $this->model_account_order->getCustomerOrders($this->request->post['customer_id']);

			foreach ($results as $result) {
				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

				$data['orders'][] = array(
					'order_id'   => $result['order_id'],
					'name'       => $result['firstname'] . ' ' . $result['lastname'],
					'status'     => $result['status'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'products'   => ($product_total + $voucher_total),
					'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'view'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], true),
				);
			}
			$json['status'] = 'success';
			$json['data']   = $data['orders'];
	
		}else{
			$json['status'] = 'error';
			$json['message'] = 'Customer Orders No found';
		}	
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}


	public function getCustomerOrderDetails() {
		
		$this->load->model('account/order');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && !empty($this->request->post) && !empty($this->request->post['customer_id']) && !empty($this->request->post['order_id'])) {
		
		$customer_id = $this->request->post['customer_id'];
		$order_id    = $this->request->post['order_id'];

		$order_info = $this->model_account_order->getCustomerOrderDetails($customer_id,$order_id);

			if ($order_info) {
					

				if ($order_info['invoice_no']) {
					$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$data['invoice_no'] = '';
				}

				$data['order_id'] = $this->request->post['order_id'];
				$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$data['payment_method'] = $order_info['payment_method'];

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$data['shipping_method'] = $order_info['shipping_method'];

				$this->load->model('catalog/product');
				$this->load->model('tool/upload');

				// Products
				$data['products'] = array();

				$products = $this->model_account_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
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

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);
					}

					$product_info = $this->model_catalog_product->getProduct($product['product_id']);

					if ($product_info) {
						$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], true);
					} else {
						$reorder = '';
					}

					$data['products'][] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
						'reorder'  => $reorder,
						'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], true)
					);
				}

				// Voucher
				$data['vouchers'] = array();

				$vouchers = $this->model_account_order->getOrderVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				// Totals
				$data['totals'] = array();

				$totals = $this->model_account_order->getOrderTotals($order_id);

				foreach ($totals as $total) {
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}

				$data['comment'] = nl2br($order_info['comment']);

				// History
				$data['histories'] = array();

				$results = $this->model_account_order->getOrderHistories($order_id);

				foreach ($results as $result) {
					$data['histories'][] = array(
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
						'status'     => $result['status'],
						'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
					);
				}
			}

			$json['status'] = 'success';
			$json['data'] = $data;	
		}else{
			$json['status'] = 'error';
			$json['message'] = 'Order Not Found';
		}	
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}

}

