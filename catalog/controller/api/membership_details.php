<?php
class ControllerApiMembershipDetails extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('membership/prices');
		$this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct($this->config->get('mpplan_product_id'));
		$this->load->model('membership/prices');
		$this->load->model('membership/subscriptions');
		
		$results = $this->model_membership_prices->getMpplans();

		$data['mpplans'] = array();
		foreach ($results as $result) {
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

			if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$result['price']) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$price = false;
			}

			$filter_data = array(
				'mpplan_id'			=> $result['mpplan_id'],
			);
			$products = $this->model_membership_prices->getProducts($filter_data);
			
			$data['mpplans'][] = array(
				'mpplan_id'			=> $result['mpplan_id'],
				'name'				=> $result['name'],
				'first_bgcolor'		=> $result['first_bgcolor'],
				'first_textcolor'	=> $result['first_textcolor'],
				'second_bgcolor'	=> $result['second_bgcolor'],
				'second_textcolor'	=> $result['second_textcolor'],
				'top_description'	=> $result['top_description'],
				'bottom_description'=> $result['bottom_description'],
				'meta_title'		=> $result['meta_title'],
				'meta_description'	=> $result['meta_description'],
				'meta_keyword'		=> $result['meta_keyword'],
				'duration'		=>		$duration,
				'duration_value'		=> $result['duration_value'],
				'status'		=> $result['status'],
				'price'				=> $price,
				'products'			=> $products,
				'informations' 		=> $this->model_membership_prices->getMpplanInfo($result['mpplan_id']),
				'href' 				=> $this->url->link('membership/plan_details', 'mpplan_id='. $result['mpplan_id'], true),
			);	
		}
		$json['status'] = 'Success';	
		
		if(isset($this->request->post['customer_id']) && !empty($this->request->post['customer_id'])){
			$data['active_plans'] =  $this->model_membership_subscriptions->getCurrentPlans($this->request->post['customer_id']);
		}else{
			$data['active_plans'] = (object)"";
		}
		$json['data'] = $data;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

	public function getMembershipDetails() {
		$this->load->language('membership/prices');
		if(empty($this->request->post['mpan_id'])){
			$json['status'] = 'error';	
			$json['data'] = 'Please Put Plan Id';
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}else{
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($this->config->get('mpplan_product_id'));
			$this->load->model('membership/prices');
			$result = $this->model_membership_prices->getMpplan($this->request->post['mpan_id']);
		
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

				if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$result['price']) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				$filter_data = array(
					'mpplan_id'			=> $result['mpplan_id'],
				);
				
				$getproducts = $this->model_membership_prices->getProducts($filter_data);
				$products 	 = array(); 
				foreach ($getproducts as $product) {				
					
					$products[] = array(
						'product_id'	=> $product['product_id'],
						'name'			=> $product['name'],
						'description'	=> $product['description'],
						'quantity'	=> $product['quantity'],
						'model'			=> $product['model'],
						'stock_status'	=> $product['stock_status'],
						'image'	=> $product['image'],
						'weight'			=> $product['weight'],
						'weight_class_id'	=> $product['weight_class_id'],
						'viewed'	=> $product['viewed']					
					);
						
				}
				$data['mpplans'][] = array(
					'mpplan_id'			=> $result['mpplan_id'],
					'name'				=> $result['name'],
					'first_bgcolor'		=> $result['first_bgcolor'],
					'first_textcolor'	=> $result['first_textcolor'],
					'second_bgcolor'	=> $result['second_bgcolor'],
					'second_textcolor'	=> $result['second_textcolor'],
					'duration'			=> $duration,
					'price'				=> $price,
					'duration'			=> $duration,
					'duration_value'	=> $result['duration_value'],
					'status'			=> $result['status'],
					'products'			=> $products,
					'informations' 		=> $this->model_membership_prices->getMpplanInfo($result['mpplan_id']),
					'href' 				=> $this->url->link('membership/plan_details', 'mpplan_id='. $result['mpplan_id'], true),
				);	
			
			$json['status'] = 'Success';	
			$json['data'] = $data['mpplans'];
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getMembershipDiscountValue() {
		$this->load->model('membership/subscriptions');
		//!empty($this->request->post['customer_group_id'])
		if(!empty($this->request->post['customer_group_id'])){
			$mplan_id 			= $this->request->post['customer_group_id'];
			$customer_group_id 	= $this->request->post['customer_group_id'];
			//$customer_id 	= $this->request->post['customer_id'];

			$discount = $this->model_membership_subscriptions->getCustomerGroupDiscount($mplan_id,$customer_group_id);
			$data 	  = array(); 
			if($this->request->post['customer_group_id']==1){
                $data['shipping_charges'] = '30';
            }else{
                $data['shipping_charges'] = '';
			}
			if($discount > 0 ){
				$data['membership_discount'] = $discount;
			}else{
				$data['membership_discount'] = '';
			}



			if ($this->config->get('total_order_discount_status')) {
				
				$cart_total = $this->request->post['sub_total'];
				$qty        = $this->request->post['qty'];
	
				$base = $this->config->get('total_order_discount_base');
				$type = $this->config->get('total_order_discount_type');
				$point = $this->config->get('total_order_discount_point');
				$discount = $this->config->get('total_order_discount_value');

				if ('Q' == $base) {
					$base = $qty;
				} elseif ('S' == $base) {
					$base = $cart_total;
				}
	
				$total_discount = 0;
	
				if (($discount > 0 && $base >= $point) || ($discount < 0 && $base < $point)) {
					if ('P' == $type) {
						$data['total_discount'] = $discount;
						$data['total_discount_type'] = 'P';
					} elseif ('F' == $type) {
						$data['total_discount'] = $discount;
						$data['total_discount_type'] = 'F';
					}
				}
	
			}
			
			$json['status'] = 	'Success';	
			$json['data'] 	=	$data;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));

		}		
		
	}

	public function getCustomerMembershipDetails(){

		$this->load->language('account/subscriptions');
		$this->load->model('membership/subscriptions');	
		// Current Plans
		if(!empty($this->request->post['customer_id'])){
			
			$current_plans = $this->model_membership_subscriptions->getActivePlan($this->request->post['customer_id']);
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($this->config->get('mpplan_product_id'));
			
			$this->load->model('membership/prices');
			$result = $this->model_membership_prices->getMpplan($current_plans['mpplan_id']);
		
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

				if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$result['price']) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				$filter_data = array('mpplan_id'=> $current_plans['mpplan_id']);
				$getproducts = $this->model_membership_prices->getProducts($filter_data);
				
				$products 	 = array(); 
				
				foreach ($getproducts as $product) {				
					
					$products[] = array(
						'product_id'	=> $product['product_id'],
						'name'			=> $product['name'],
						'description'	=> $product['description'],
						'quantity'	=> $product['quantity'],
						'model'			=> $product['model'],
						'stock_status'	=> $product['stock_status'],
						'image'	=> $product['image'],
						'weight'			=> $product['weight'],
						'weight_class_id'	=> $product['weight_class_id'],
						'viewed'	=> $product['viewed']					
					);
						
				}
				$data['mpplans'][] = array(
					'mpplan_id'			=> $result['mpplan_id'],
					'name'				=> $result['name'],
					'first_bgcolor'		=> $result['first_bgcolor'],
					'first_textcolor'	=> $result['first_textcolor'],
					'second_bgcolor'	=> $result['second_bgcolor'],
					'second_textcolor'	=> $result['second_textcolor'],
					'duration'			=> $duration,
					'price'				=> $price,
					'duration'			=> $duration,
					'start_date'	=> date($this->language->get('active_date_format'), strtotime($current_plans['start_date'])),
					'end_date'		=> date($this->language->get('active_date_format'), strtotime($current_plans['end_date'])),
					'duration_value'	=> $result['duration_value'],
					'status'			=> $result['status'],
					'products'			=> $products,
					'informations' 		=> $this->model_membership_prices->getMpplanInfo($result['mpplan_id']),					
				);	
			$json['status'] = 'Success';	
			$json['data'] = $data['mpplans'];
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));



		/*		
			foreach ($current_plans as $current_plan) {
				$data['current_plans'][] = array(
					'mpplan_customer_id'		=> $current_plan['mpplan_customer_id'],
					'active'		=> $current_plan['active'],
					'plan_name'		=> $current_plan['plan_name'],
					'start_date'	=> date($this->language->get('active_date_format'), strtotime($current_plan['start_date'])),
					'end_date'		=> date($this->language->get('active_date_format'), strtotime($current_plan['end_date'])),
					'status'		=> $this->language->get('text_activate'),
					'href'			=> $this->url->link('membership/plan_details','mpplan_id='. $current_plan['mpplan_id'], true),
				);
			}
		}
		*/
		}	
	}
	
}
