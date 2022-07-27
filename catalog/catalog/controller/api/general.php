<?php
class ControllerApiGeneral extends Controller
{
    public function getHomeBanners()
    {
        $this->load->model('design/banner');
        $this->load->model('tool/image');

        $data['banners'] = array();

        $results = $this->model_design_banner->getBanner(7); // HERE 7 For Home Page Slider

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $data['banners'][] = array(
                    'title' => $result['title'],
                    'link' => $result['link'],
                    'image' => HTTPS_SERVER . 'image/' . $result['image'],
                );
            }
        }
        $json['status'] = 'Success';
        $json['data'] = $data['banners'];
        //$json['sale_text'] = 'FREE EXTRA VIRGIN COLD PRESSED AVOCADO OIL ON CART VALUE  OF MINIMUM 1500/-*';
        $json['sale_text'] = '';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getStates()
    {
        $this->load->model('localisation/zone');
        $country_id = 99;
        $zones = $this->model_localisation_zone->getZonesByCountryId($country_id);
        $json['status'] = 'Success';
        $json['data'] = $zones;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function buyMembership()
    {
        $json = array();
        //$prod = "[{end_date=2021-04-25 16:25:53, total=3, price=2, duration_type=d, mpplan_id=3, plan_info=, plan_name=Gold, start_date=2020-04-25 16:25:53, duration_value=365}]";
        // print_r($prod[0]);exit;
        $this->load->language('api/order');
        $this->load->model('setting/extension');
        $headers = "From: mittalankur088@gmail.com" . "\r\n" .
            "CC: roshansingh9450@gmail.com";
        //mail("mittalankur088@gmail.com","Data",json_encode($this->request->post,$headers));
        if (!isset($this->request->post['customer_id'])) {
            mail("mittalankur088@gmail.com", "Data", json_encode($this->request->post, $headers));
            $json['data'] = json_decode();
            $json['error'] = $this->language->get('error_permission');

        } else {

            if (1) {
                //print_r($this->request->post['products']);
                //print_r(json_decode($this->request->post['products']));
                //exit;
                $json['success'] = $this->language->get('text_success');

                $order_data = array();
                // Store Details
                $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                $order_data['store_id'] = $this->config->get('config_store_id');
                $order_data['store_name'] = $this->config->get('config_name');
                $order_data['store_url'] = $this->config->get('config_url');

                // Customer Details
                $order_data['customer_id'] = $this->request->post['customer_id'];
                $order_data['customer_group_id'] = $this->request->post['customer_group_id'];
                $order_data['firstname'] = $this->request->post['firstname'];
                $order_data['lastname'] = $this->request->post['lastname'];
                $order_data['email'] = $this->request->post['email'];
                $order_data['telephone'] = $this->request->post['telephone'];
                $order_data['custom_field'] = '{"1":"0000"}';

                // Payment Details
                $order_data['payment_firstname'] = $this->request->post['payment_firstname'];
                $order_data['payment_lastname'] = $this->request->post['payment_lastname'];
                $order_data['payment_company'] = '';
                $order_data['payment_address_1'] = $this->request->post['payment_address_1'];
                $order_data['payment_address_2'] = $this->request->post['payment_address_2'];
                $order_data['payment_city'] = $this->request->post['payment_city'];
                $order_data['payment_postcode'] = $this->request->post['payment_postcode'];
                $order_data['payment_zone'] = $this->request->post['payment_zone'];
                $order_data['payment_zone_id'] = $this->request->post['payment_zone_id'];
                $order_data['payment_country'] = 'India';
                $order_data['payment_country_id'] = 99;
                $order_data['payment_address_format'] = '';
                $order_data['payment_custom_field'] = '[]';

                if (isset($this->request->post['payment_method_title'])) {
                    $order_data['payment_method'] = $this->request->post['payment_method_title'];
                } else {
                    $order_data['payment_method'] = '';
                }

                if (isset($this->request->post['payment_code'])) {
                    $order_data['payment_code'] = $this->request->post['payment_code'];
                } else {
                    $order_data['payment_code'] = '';
                }

                $order_data['shipping_firstname'] = $this->request->post['payment_firstname'];
                $order_data['shipping_lastname'] = $this->request->post['payment_lastname'];
                $order_data['shipping_company'] = '';
                $order_data['shipping_address_1'] = $this->request->post['payment_address_1'];
                $order_data['shipping_address_2'] = $this->request->post['payment_address_2'];
                $order_data['shipping_city'] = $this->request->post['payment_city'];
                $order_data['shipping_postcode'] = $this->request->post['payment_postcode'];
                $order_data['shipping_zone'] = $this->request->post['payment_zone'];
                $order_data['shipping_zone_id'] = $this->request->post['payment_zone_id'];
                $order_data['shipping_country'] = 'India';
                $order_data['shipping_country_id'] = '99';
                $order_data['shipping_address_format'] = '[]';
                $order_data['shipping_custom_field'] = array();
                $order_data['shipping_method'] = '';
                $order_data['shipping_code'] = '';
                $order_data['affiliate_id'] = '';
                $order_data['commission'] = '';
                $order_data['marketing_id'] = '';
                $order_data['tracking'] = '';
                $order_data['shipping_cost'] = $this->request->post['shipping_cost'];

                /* Membership Details*/

                $this->load->model('catalog/product');
                $product_info = $this->model_catalog_product->getProduct($this->config->get('mpplan_product_id'));

                $this->load->model('membership/prices');
                $result = $this->model_membership_prices->getMpplan($this->request->post['mplan_id']);

                $data['mpplans'] = array();

                $duration = '';
                switch ($result['duration_type']) {
                    case 'd':
                        $duration = sprintf($this->language->get('text_days'), $result['duration_value']);
                        break;
                    case 'm':
                        $duration = sprintf($this->language->get('text_months'), $result['duration_value']);
                        break;
                    case 'y':
                        $duration = sprintf($this->language->get('text_years'), $result['duration_value']);
                        break;
                }

                if ($result['price']) {
                    // $price = $this->tax->calculate($result['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                    $price = $result['price'];
                } else {
                    $price = false;
                }

                $today_date = date("Y-m-d H:i:s");
                $expiry_date = date('Y-m-d H:i:s', strtotime($today_date . ' 364 days'));

                $this->request->post['products'] = array();
                $this->request->post['products'][0] = array(
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name'],
                    'description' => $product_info['description'],
                    'quantity' => 1,
                    'model' => $product_info['model'],
                    'shipping' => $product_info['shipping'],
                    'tax_class_id' => $product_info['tax_class_id'],
                    'viewed' => $product_info['viewed'],
                    'mpplan_id' => $result['mpplan_id'],
                    'name' => $result['name'],
                    'price' => $price,
                    'total' => $price,
                    'status' => $result['status'],
                    'plan_name' => $result['name'],
                    'start_date' => $today_date,
                    'end_date' => $expiry_date,
                    'duration_type' => $duration,
                    'duration_value' => $result['duration_value'],
                    'plan_info' => '',

                );

                $this->request->post['products'][0]['option'][] = array(
                    'option_id' => 0,
                    'product_option_id' => 0,
                    'product_option_value_id' => 0,
                    'name' => 'Plan',
                    'value' => $result['name'],
                    'option_value_id' => 0,
                    'type' => 'mpplan_name',
                );

                $this->request->post['products'][0]['option'][] = array(
                    'option_id' => 0,
                    'product_option_id' => 0,
                    'product_option_value_id' => 0,
                    'option_value_id' => 0,
                    'name' => 'Duration',
                    'value' => $result['duration_value'] . ' Days',
                    'type' => 'mpplan_duration',
                );

                $order_data['products'] = array();
                $order_data['products'][] = array(
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name'],
                    'description' => $product_info['description'],
                    'quantity' => 1,
                    'model' => $product_info['model'],
                    'shipping' => $product_info['shipping'],
                    'tax_class_id' => $product_info['tax_class_id'],
                    'viewed' => $product_info['viewed'],
                    'tax' => 0,
                    'reward' => 0,
                    'mpplan_id' => $result['mpplan_id'],
                    'name' => $result['name'],
                    'price' => $price,
                    'total' => $price,
                    'status' => $result['status'],
                    'plan_name' => $result['name'],
                    'start_date' => $today_date,
                    'end_date' => $expiry_date,
                    'duration_type' => 'd',
                    'duration_value' => $result['duration_value'],
                    'plan_info' => '',

                );

                $order_data['products'][0]['option'][] = array(
                    'option_id' => 0,
                    'product_option_id' => 0,
                    'product_option_value_id' => 0,
                    'name' => 'Plan',
                    'value' => $result['name'],
                    'option_value_id' => 0,
                    'type' => 'mpplan_name',
                );

                $order_data['products'][0]['option'][] = array(
                    'option_id' => 0,
                    'product_option_id' => 0,
                    'product_option_value_id' => 0,
                    'option_value_id' => 0,
                    'name' => 'Duration',
                    'value' => $result['duration_value'] . ' Days',
                    'type' => 'mpplan_duration',
                );
                //print_r($this->request->post['products']);exit;
                /* Membership Details Ends */
                // Products
                /*
                $order_data['products'] = array();
                $this->request->post['products'][0]['product_id']       =  75;
                $this->request->post['products'][0]['name']             =  "Membership Product";
                $this->request->post['products'][0]['quantity']         =  1;
                $this->request->post['products'][0]['tax_class_id']     =  0;
                $this->request->post['products'][0]['model']            =  "Membership Product";
                $this->request->post['products'][0]['shipping']         =  0; */
                /*
                foreach ($this->request->post['products'] as $product) {

                $product['option'][]         =  array(
                'option_id'=>0,
                'product_option_id' => 0,
                'product_option_value_id' => 0,
                'name'                    => 'Plan',
                'value'                   => $product['plan_name'],
                'option_value_id'=>         0,
                'type'                    => 'mpplan_name'
                );

                $product['option'][]         =  array(
                'option_id'=>0,
                'product_option_id' => 0,
                'product_option_value_id' => 0,
                'option_value_id'=>         0,
                'name'                    => 'Duration',
                'value'                   => $product['duration_value'].' Days',
                'type'                    => 'mpplan_duration'
                );

                $option_data = array();

                if(!empty($product['option'])){
                foreach ($product['option'] as $option) {
                $option_data[] = array(
                'product_option_id'       => $option['product_option_id'],
                'product_option_value_id' => $option['product_option_value_id'],
                'option_id'               => $option['option_id'],
                'option_value_id'         => $option['option_value_id'],
                'name'                    => $option['name'],
                'value'                   => $option['value'],
                'type'                    => $option['type']
                );
                }

                }else{
                $option_data = array();
                }

                $mpplan_details = array();

                if(!empty($product['mpplan_id'])){
                $mpplan_details = array(
                'mpplan_id' => $product['mpplan_id'],
                'plan_name'      => $product['plan_name'],
                'start_date'     => $product['start_date'],
                'end_date'       => $product['end_date'],
                'duration_type'  => $product['duration_type'],
                'duration_value' => $product['duration_value'],
                'plan_info'      => $product['plan_info']
                );
                }

                $order_data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'quantity'   => $product['quantity'],
                'price'      => $product['price'],
                'total'      => $product['total'],
                'option'     => $option_data,
                'tax_class_id'=> $product['tax_class_id'],
                'shipping'  => $product['shipping'],
                'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                'reward'     => 0,
                'mpplan_details'=>$mpplan_details
                );

                }
                 */

                $this->load->model('extension/total/api/general');
                $totals = array();
                $taxes = $this->model_extension_total_api_general->getTaxes($order_data['products']);
                $total = 0;

                $total_data = array(
                    'totals' => &$totals,
                    'taxes' => &$taxes,
                    'total' => &$total,
                );

                $sort_order = array();

                $results = $this->model_setting_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);
                $i = 1;

                foreach ($results as $result) {
                    if ($this->config->get('total_' . $result['code'] . '_status')) {

                        if (in_array($result['code'], array('sub_total', 'shipping', 'tax', 'order_discount', 'membership_subscriptions', 'total'))) {

                            $this->load->model('extension/total/api/' . $result['code']);

                            $this->{'model_extension_total_api_' . $result['code']}->getTotal($total_data, $order_data);

                        }

                    }
                }
                //print_r($total_data);exit;
                $sort_order = array();

                foreach ($total_data['totals'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data['totals']);

                $order_data = array_merge($order_data, $total_data);

                if (isset($this->request->post['comment'])) {
                    $order_data['comment'] = "Payment Successful. Razorpay Payment Id:" . $this->request->post['txn_id'];
                } else {
                    $order_data['comment'] = '';
                }

                $order_data['language_id'] = $this->config->get('config_language_id');
                $order_data['currency_id'] = 4;
                $order_data['currency_code'] = 'INR';
                $order_data['currency_value'] = 1;
                $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

                if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                    $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                    $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
                } else {
                    $order_data['forwarded_ip'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data['user_agent'] = '';
                }

                if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                    $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
                } else {
                    $order_data['accept_language'] = '';
                }

                $this->load->model('api/order');
                //echo "exit";exit;
                //print_r($order_data);exit;
                $json['order_id'] = $this->model_api_order->addOrder($order_data);

                // Set the order history
                if (isset($this->request->post['order_status_id'])) {
                    $order_status_id = 5;
                } else {
                    $order_status_id = 5;
                }
                $this->model_api_order->addOrderHistory($json['order_id'], $order_status_id, $order_data['comment'], $notify = false, $override = false, $mplan = 1);
                $json['status'] = "Success";
                $json['status'] = "Order is created Successfully";
                $json['data'] = array(
                    'order_id' => $json['order_id'],
                );
                //print_r($order_data);exit;

            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addCustomerOrder() {
		$json = array();

		$this->load->language('api/order');
		$this->load->model('setting/extension');

		if (!isset($this->request->post['customer_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {

			if (1) {
                
				//$headers = "From: mittalankur088@gmail.com" . "\r\n" ."CC: roshansingh9450@gmail.com";
				//mail("mittalankur088@gmail.com","Data",json_encode($this->request->post),$headers);
				$prod_array = explode(",", $this->request->post['products_data']);
				foreach ($prod_array as $product_val) {
					$prod = explode("|", $product_val);
					$this->request->post['products'][] = array(
						'product_id' => $prod[0],
						'name' => $prod[1],
						'model' => $prod[2],
						'quantity' => $prod[3],
						'price' => $prod[4],
						'total' => ($prod[3] * $prod[4]),
						'tax_class_id' => $prod[5],
						'shipping' => $prod[6],
					);
				}

				$json['success'] = $this->language->get('text_success');

				$order_data = array();

				// Store Details
				$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
				$order_data['store_id'] = $this->config->get('config_store_id');
				$order_data['store_name'] = $this->config->get('config_name');
				$order_data['store_url'] = $this->config->get('config_url');

				// Customer Details
				$order_data['customer_id'] = $this->request->post['customer_id'];
				$order_data['customer_group_id'] = $this->request->post['customer_group_id'];
				$order_data['firstname'] = $this->request->post['firstname'];
				$order_data['lastname'] = $this->request->post['lastname'];
				$order_data['email'] = $this->request->post['email'];
				$order_data['telephone'] = $this->request->post['telephone'];
				$order_data['custom_field'] = '{"1":"0000"}';

				// Payment Details
				$order_data['payment_firstname'] = $this->request->post['payment_firstname'];
				$order_data['payment_lastname'] = $this->request->post['payment_lastname'];
				$order_data['payment_company'] = '';
				$order_data['payment_address_1'] = $this->request->post['payment_address_1'];
				$order_data['payment_address_2'] = $this->request->post['payment_address_2'];
				$order_data['payment_city'] = $this->request->post['payment_city'];
				$order_data['payment_postcode'] = $this->request->post['payment_postcode'];
				$order_data['payment_zone'] = $this->request->post['payment_zone'];
				$order_data['payment_zone_id'] = $this->request->post['payment_zone_id'];
				$order_data['payment_country'] = 'India';
				$order_data['payment_country_id'] = 99;
				$order_data['payment_address_format'] = '';
				$order_data['payment_custom_field'] = '[]';

				$order_data['shipping_cost'] = $this->request->post['shipping_cost'];

				if (isset($this->request->post['payment_method'])) {
					$order_data['payment_method'] = $this->request->post['payment_method'];
				} else {
					$order_data['payment_method'] = '';
				}

				if (isset($this->request->post['payment_code'])) {
					$order_data['payment_code'] = 'Credit Card / Debit Card / Net Banking (Razorpay)';
				} else {
					$order_data['payment_code'] = 'Credit Card / Debit Card / Net Banking (Razorpay)';
				}

				$order_data['shipping_firstname'] = $this->request->post['payment_firstname'];
				$order_data['shipping_lastname'] = $this->request->post['payment_lastname'];
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = $this->request->post['payment_address_1'];
				$order_data['shipping_address_2'] = $this->request->post['payment_address_2'];
				$order_data['shipping_city'] = $this->request->post['payment_city'];
				$order_data['shipping_postcode'] = $this->request->post['payment_postcode'];
				$order_data['shipping_zone'] = $this->request->post['payment_zone'];
				$order_data['shipping_zone_id'] = $this->request->post['payment_zone_id'];
				$order_data['shipping_country'] = $order_data['payment_country'];
				$order_data['shipping_country_id'] = $order_data['payment_country_id'];
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = array();
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';

				$order_data['affiliate_id'] = '';
				$order_data['commission'] = '';
				$order_data['marketing_id'] = '';
				$order_data['tracking'] = '';
				$order_data['coupon'] = $this->request->post['coupon'];
				$order_data['voucher'] = $this->request->post['voucher'];
				// Products
				$order_data['products'] = array();

				foreach ($this->request->post['products'] as $product) {

					$option_data = array();

					if (!empty($product['option'])) {
						foreach ($product['option'] as $option) {
							$option_data[] = array(
								'product_option_id' => $option['product_option_id'],
								'product_option_value_id' => $option['product_option_value_id'],
								'option_id' => $option['option_id'],
								'option_value_id' => $option['option_value_id'],
								'name' => $option['name'],
								'value' => $option['value'],
								'type' => $option['type'],
							);
						}
					} else {
						$option_data = array();
					}

					$order_data['products'][] = array(
						'product_id' => $product['product_id'],
						'name' => $product['name'],
						'model' => $product['model'],
						'quantity' => $product['quantity'],
						'price' => $product['price'],
						'total' => $product['total'],
						'option' => $option_data,
						'tax_class_id' => $product['tax_class_id'],
						'shipping' => $product['shipping'],
						'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
						'reward' => 0,
					);
				}
				$this->load->model('extension/total/api/general');
				$totals = array();
				$taxes = $this->model_extension_total_api_general->getTaxes($order_data['products']);
				$total = 0;

				$total_data = array(
					'totals' => &$totals,
					'taxes' => &$taxes,
					'total' => &$total,
				);

				$sort_order = array();

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {

						if (in_array($result['code'], array('coupon', 'voucher', 'sub_total', 'shipping', 'tax', 'order_discount', 'membership_subscriptions', 'total'))) {
							echo $result['code'];
							$this->load->model('extension/total/api/' . $result['code']);
							$this->{'model_extension_total_api_' . $result['code']}->getTotal($total_data, $order_data);

						}

					}
				}
				//print_r($total_data);exit;
				$sort_order = array();

				foreach ($total_data['totals'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data['totals']);

				$order_data = array_merge($order_data, $total_data);

				if (isset($this->request->post['comment'])) {
					$order_data['comment'] = $this->request->post['comment'];
				} else {
					$order_data['comment'] = '';
				}

				$order_data['language_id'] = $this->config->get('config_language_id');
				$order_data['currency_id'] = 4;
				$order_data['currency_code'] = 'INR';
				$order_data['currency_value'] = 1;
				$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

				if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
					$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
				} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
					$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
				} else {
					$order_data['forwarded_ip'] = '';
				}

				if (isset($this->request->server['HTTP_USER_AGENT'])) {
					$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
				} else {
					$order_data['user_agent'] = '';
				}

				if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
					$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
				} else {
					$order_data['accept_language'] = '';
				}
                
				$this->load->model('checkout/order');
				if (!empty($this->request->post['txn_id'])) {
					$json['order_id'] = $this->model_checkout_order->addOrder($order_data);

					// Set the order history
					if (isset($this->request->post['order_status_id'])) {
						$order_status_id = 5;
					} else {
						$order_status_id = $this->config->get('config_order_status_id');
					}
					$comment = "Payment Successful.Payment transaction id:" . $this->request->post['txn_id'];

                    $this->model_checkout_order->addOrderHistory($json['order_id'], $order_status_id, $comment);
                    if(isset($order_data['payment_method']) && ($order_data['payment_method']=='wallet')){
                        $this->load->model('api/e_wallet');
                        $d = array('amount' => $order_data['total'], 'order_id'=>$json['order_id'],  'customer_id' => $order_data['customer_id']);
				        $this->model_api_e_wallet->orderAmountDeducted($d);                    
                    }                    
					$json['status'] = 'success';
					//echo $json['order_id'];exit;
					//print_r($order_data);exit;
				} else {
					$json['status'] = 'error';
					$json['data'] = 'error in transaction Id';
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    
    public function getShippingCharges()
    {
        $this->load->model('membership/subscriptions');
        //!empty($this->request->post['customer_group_id'])
        if (!empty($this->request->post['customer_group_id'])) {
            if ($this->request->post['customer_group_id'] == 1) {
                $shipping_charges = 30;
            } else {
                $shipping_charges = 0;
            }
            $json['status'] = 'Success';
            $json['data'] = $shipping_charges;
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));

        }

    }

    public function getCouponDetails()
    {

        $this->load->model('extension/total/api/coupon');
        $response = array();
        if (isset($this->request->post['coupon_code']) && !empty($this->request->post['coupon_code'])) {
            $response['status'] = 'success';

            $coupon_code = $this->request->post['coupon_code'];
            $data['total'] = $this->request->post['total'];
            $data['customer_id'] = $this->request->post['customer_id'];
            $data['products'] = array();
            if (!empty($this->request->post['product_ids'])) {
                $prod_array = explode(",", $this->request->post['product_ids']);
                foreach ($prod_array as $key => $prod) {
                    array_push($data['products'], array('product_id' => $prod));
                }
            }
            $results = $this->model_extension_total_api_coupon->getCoupon($this->request->post['coupon_code'], $data);
            //print_r($results);exit;
            if (is_array($results) && isset($results['error']) && !empty($results['error'])) {
                if (isset($results['reason'])) {
                    $response['message'] = $results['reason'];
                }
                $response['data'] = array();
            } else {
                $response['message'] = 'coupon details found';
                $response['data'][] = $results;
            }
        } else {
            $response['status'] = 401;
            $response['message'] = 'coupon code not found';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }
    public function getVoucherDetails()
    {

        $this->load->model('extension/total/api/voucher');
        $response = array();
        if (isset($this->request->post['voucher_code']) && !empty($this->request->post['voucher_code'])) {
            $response['status'] = 'success';

            $coupon_code = $this->request->post['voucher_code'];

            $results = $this->model_extension_total_api_voucher->getVoucher($this->request->post['voucher_code']);
            if (empty($results)) {
                $response['message'] = 'Voucher code in invalid.';
                $response['data'] = array();
            } else {
                $response['message'] = 'Voucher Details found';
                $response['data'][] = $results;
            }
        } else {
            $response['status'] = 'bad_request';
            $response['message'] = 'Voucher code not found';
            $response['data'] = array();
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }
    public function getHashKey()
    {

        /*
        $key        =   $_POST["key"];
        $salt       =   $_POST["key"];
        $txnId      =   $_POST["txnid"];
        $amount     =   $_POST["amount"];
        $productName=   $_POST["productInfo"];
        $firstName  =   $_POST["firstName"];
        $email      =   $_POST["email"];
        $udf1       =   $_POST["udf1"];
        $udf2       =   $_POST["udf2"];
        $udf3       =   $_POST["udf3"];
        $udf4       =   $_POST["udf4"];
        $udf5       =   $_POST["udf5"];
         */
        $key = 'baGULBcs';
        $salt = 'z9FZyyBfjv';
        $txnId = time();
        $amount = $_POST["amount"];
        $productName = $_POST["product"];
        $firstName = $_POST["name"];
        $email = $_POST["email"];
        $udf1 = '';
        $udf2 = '';
        $udf3 = '';
        $udf4 = '';
        $udf5 = '';

        $payhash_str = $key . '|' . ($txnId) . '|' . ($amount) . '|' . ($productName) . '|' . ($firstName) . '|' . ($email) . '|' . ($udf1) . '|' . ($udf2) . '|' . ($udf3) . '|' . ($udf4) . '|' . ($udf5) . '|||||||' . $salt;

        $hash = strtolower(hash('sha512', $payhash_str));

        $arr['data'] = array('key' => $hash);
        $arr['status'] = 1;
        $arr['errorCode'] = null;
        $arr['responseCode'] = null;
        $output = $arr;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($output));

    }

    public function getAppVersion()
    {
        $response['status'] = 'success';
        $response['appVersion'] = '1.1.8';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    public function getreviews()
    {

        $this->load->language('product/product');
        $this->load->model('catalog/review');

        $page = 1;

        $data['reviews'] = array();
        $review_total = $this->model_catalog_review->getTotalReviewsByProductId($_POST['product_id']);
        $results = $this->model_catalog_review->getReviewsByProductId($_POST['product_id'], ($page - 1) * 5, 500);

        foreach ($results as $result) {
            $data['reviews'][] = array(
                'author' => $result['author'],
                'text' => nl2br($result['text']),
                'rating' => (int) $result['rating'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            );
        }

        if (!empty($data)) {
            $response['status'] = 'success';
            $response['message'] = 'Reviews Found';
            $response['data'][] = $data;
        } else {
            $response['status'] = 'false';
            $response['message'] = 'Reviews Not Found';
            $response['data'][] = array();
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    public function write()
    {
        $this->load->language('product/product');
        $json = array();
        //print_r($_POST);exit;
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($_POST['name']) < 3) || (utf8_strlen($_POST['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($_POST['text']) < 25) || (utf8_strlen($_POST['text']) > 1000)) {
                $json['error'] = $this->language->get('error_text');
            }

            if (empty($_POST['rating']) || $_POST['rating'] < 0 || $_POST['rating'] > 5) {
                $json['error'] = $this->language->get('error_rating');
            }

            if (!isset($json['error'])) {
                $this->load->model('catalog/review');
                $this->model_catalog_review->addReview($_POST['product_id'], $_POST);
                $json['success'] = $this->language->get('text_success');
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getFeebieProducts()
    {
        $response = array();
        $get_freebie_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "freebie_config");
		if ($get_freebie_query->num_rows > 0) {
            $response['status'] = 'success';
            $response['data'] = $get_freebie_query->row;
            $products_array = explode(",",$get_freebie_query->row['product_ids']);
            foreach($products_array as $key=>$prod){
                $response['data']['products'][] = array('product_id'=>$prod,'qty'=>$get_freebie_query->row['qty']);
            }            
        }else{
            $response['status'] = 'error';
            $response['data'] = array();
        }       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

}
