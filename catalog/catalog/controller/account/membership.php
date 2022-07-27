<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class ControllerAccountMembership extends Controller {



	public function index() {

		if (!$this->customer->isLogged()) {
		
			$this->response->redirect($this->url->link('account/login', '', true));

		}

		$this->load->language('account/membership');

		$this->load->model('account/membership');
		$this->load->model('membership/subscriptions');


		$day=$this->model_membership_subscriptions->getenabbleMembership();
	
		$currentDay=date('l');
		$isAllow=1;
		foreach ($day as $key => $value) {
				if($value['name']===$currentDay){
						$isAllow=2;

				}
		}		

		if($isAllow==1){
			$this->response->redirect($this->url->link('account/subscriptions', '', true));

		}

		
		$userId=$this->session->data['customer_id'];
		$this->document->setTitle($this->language->get('heading_title'));
		$count=$this->model_account_membership->getselectedday($userId);

		if($count==0){
			$this->response->redirect($this->url->link('account/deliverysettings', '', true));
			exit;
		}
		
		$monday = date('Y-m-d', strtotime( 'monday this week' ) );
		$customerExist  = array();
		$customerExist['week_date'] = $monday;	
		$customerExist['customer_id'] = $userId;
		$weekorder = $this->model_account_membership->checkweekorderexsit($customerExist);
		



		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_home'),

			'href' => $this->url->link('common/home')

		);
		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_account'),

			'href' => $this->url->link('account/account', '', true)

		);

		if (!isset($this->session->data['customer_id'])) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
	 	$mebsip=$this->model_account_membership->getMemebership($userId);
	 	if(empty($mebsip)){
 			$this->response->redirect($this->url->link('account/account', '', true));

	 	}
	 	$mebsipId=$mebsip['mpplan_id'];
	 	

	 	if(!$mebsipId){
	 		$this->response->redirect($this->url->link('account/account', '', true));

	 	}else{
	 		$mcat=$this->model_account_membership->getMemebershipprdcat($mebsipId);
	 		$parra=array();
	 		foreach ($mcat as $key => $value) {
	 				$parra[]=$value['category_id'];
	 			}	
	 	}
	 	


		$data['products'] = array();
		$filter_data = array(
				'filter_category_id' => $parra,
		);

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$results=$this->model_account_membership->getProducts($filter_data);
		$url='';
		
		foreach ($results as $result) {

	                $img = $this->model_catalog_product->getProductImages($product_info['product_id']);
					foreach ($img as $result) {
					    $images[] = array(
					        'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
					        'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
					    );
					};
	               

			
				if (isset($imagedata[0]['image'])) {

					$image = $this->model_tool_image->resize($imagedata[0]['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));

				} else {
					


					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));

				}



				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {

					$price = $this->currency->format($this->tax->calculate(isset($result['price']), isset($result['tax_class_id']), $this->config->get('config_tax')), $this->session->data['currency']);

				} else {

					$price = false;

				}


				//By Ankur Mittal
				$discount = 0 ;
				if ((float)$result['special']) { 
					
					$discount = (($result['price'] - $result['special']) / $result['price']) * 100; 
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				} else {

					$special = false;

				}



				if ($this->config->get('config_tax')) {

					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);

				} else {

					$tax = false;

				}



				if ($this->config->get('config_review_status')) {

					$rating = (int)$result['rating'];

				} else {

					$rating = false;

				}


				/* inspire Images Start */



				$insp_data['insp_images'] = array();

				$insp_results = $this->model_catalog_product->getProductImages($result['product_id']);


				foreach ($insp_results as $insp_result) {

					if(!empty($insp_result['image'])){
						$insp_data['insp_images'][] = $insp_result['image'];

					}else{
						$insp_data=array();

					}
				}

				
				//Weight data


				$wresult=$this->model_account_membership->getProductweight($result['product_id']);
				$dresult=$this->model_account_membership->getlistdelivery($userId);

				if($result['quantity']!=0){
		
				$data['products'][] = array(

					'product_id'  => $result['product_id'],

					'thumb'       => $image,

					'name'        => $result['name'],

					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',

					'price'       => isset($price)?$price:0.00,
				     // Add images Data 
					 'image' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height')),

					'insp_images' => !empty($insp_data['insp_images'][0]['popup'])?$insp_data['insp_images'][0]['popup']:'',

					//End
					'wresult'=>$wresult,
					'dresult'=>$dresult,


					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)

				);
			}

		}
		//print_r($data['products']);exit;
		
		$totalweight=$this->model_account_membership->getCustomerWeightAllowded($userId);
	
		$timeslot=	$count=$this->model_account_membership->getTimeslot();

		$dsetting=	$count=$this->model_account_membership->getlistdelivery($userId);
		$data['timeslot'] = $timeslot;
		$data['dsetting'] = $dsetting;
		$data['totalweight'] = $totalweight;
		$data['kgweight'] =number_format($totalweight/1000,2);
		$data['backlink'] = $this->url->link('account/deliverysettings', '', true);
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if($weekorder){
				$this->load->model('account/membership');
			$userId=$this->session->data['customer_id'];
			$results = $this->model_account_membership->getcurrentVegOrderDetails($userId);


			$sum=0;
			$fd=date("w") + 2;
			$flag=0;
			foreach ($results as $result) {
				$sum=$sum+$result['wgt'];
				$data['customers'][] = array(

				'veg_order_id' => $result['veg_order_id'],

				'product_id' => $result['product_id'],

				'slotname' => $result['slotname'],

				'weight_id' => $result['weight_id'],

				'delivery_id' => $result['delivery_id'],
				'dname' => $result['dname'],

				'model' => $result['model'],

					'view_link' => $this->url->link('account/membership/vieworder', '&orderId=' . $result['veg_order_id'],true),	
				'name' => $result['name'],
				'totalweight' => $result['total_weight']/1000,

				'weight_value' => $result['weight_value'],

				'date_added' => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
				);
				$data['wgt']=$sum;
				if($result['delivery_id']>=$fd){
					$flag++;

				}

			}

			if($flag==count($results)){
				$data['edit_link']=$this->url->link('account/deliverysettings/editOrder');
			}else{
				$data['edit_link']='';
			}


			$this->response->setOutput($this->load->view('membership/my_membership_order', $data));
		}else{
			$this->response->setOutput($this->load->view('membership/membership', $data));
		}

	}	

	public function editmembership(){
			if (!$this->customer->isLogged()) {
		
			$this->response->redirect($this->url->link('account/login', '', true));

		}

		$this->load->language('account/membership');

		$this->load->model('account/membership');
		$this->load->model('membership/subscriptions');


		$day=$this->model_membership_subscriptions->getenabbleMembership();
	
		$currentDay=date('l');
		$isAllow=1;
		foreach ($day as $key => $value) {
				if($value['name']===$currentDay){
						$isAllow=2;

				}
		}		

		if($isAllow==1){
			$this->response->redirect($this->url->link('account/subscriptions', '', true));

		}

		
		$userId=$this->session->data['customer_id'];
		$this->document->setTitle($this->language->get('heading_title'));
		$count=$this->model_account_membership->getselectedday($userId);

		if($count==0){
			$this->response->redirect($this->url->link('account/deliverysettings', '', true));
			exit;
		}
		
		$monday = date('Y-m-d', strtotime( 'monday this week' ) );
		$customerExist  = array();
		$customerExist['week_date'] = $monday;	
		$customerExist['customer_id'] = $userId;
		$weekorder = $this->model_account_membership->checkweekorderexsit($customerExist);
		



		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_home'),

			'href' => $this->url->link('common/home')

		);
		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_account'),

			'href' => $this->url->link('account/account', '', true)

		);

		if (!isset($this->session->data['customer_id'])) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
	 	$mebsip=$this->model_account_membership->getMemebership($userId);
	 	if(empty($mebsip)){
 			$this->response->redirect($this->url->link('account/account', '', true));

	 	}
	 	$mebsipId=$mebsip['mpplan_id'];
	 	

	 	if(!$mebsipId){
	 		$this->response->redirect($this->url->link('account/account', '', true));

	 	}else{
	 		$mcat=$this->model_account_membership->getMemebershipprdcat($mebsipId);
	 		$parra=array();
	 		foreach ($mcat as $key => $value) {
	 				$parra[]=$value['category_id'];
	 			}	
	 	}
	 	


		$data['products'] = array();
		$filter_data = array(
				'filter_category_id' => $parra,
				'customer_id'=>$userId
		);

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$results=$this->model_account_membership->getProducts($filter_data);
		$orderProduct=$this->model_account_membership->getVegOrderLineItems($filter_data);
		//print_r($orderProduct);
	

		$url='';
		$weight=0;
		$pweight=array();
		$chked='';
		foreach ($results as $result) {
					
				
	                $img = $this->model_catalog_product->getProductImages($product_info['product_id']);
					foreach ($img as $result) {
					    $images[] = array(
					        'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
					        'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
					    );
					};
	               

			
				if (isset($imagedata[0]['image'])) {

					$image = $this->model_tool_image->resize($imagedata[0]['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));

				} else {
					


					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));

				}



				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {

					$price = $this->currency->format($this->tax->calculate(isset($result['price']), isset($result['tax_class_id']), $this->config->get('config_tax')), $this->session->data['currency']);

				} else {

					$price = false;

				}


				//By Ankur Mittal
				$discount = 0 ;
				if ((float)$result['special']) { 
					
					$discount = (($result['price'] - $result['special']) / $result['price']) * 100; 
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				} else {

					$special = false;

				}



				if ($this->config->get('config_tax')) {

					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);

				} else {

					$tax = false;

				}



				if ($this->config->get('config_review_status')) {

					$rating = (int)$result['rating'];

				} else {

					$rating = false;

				}


				/* inspire Images Start */



				$insp_data['insp_images'] = array();

				$insp_results = $this->model_catalog_product->getProductImages($result['product_id']);


				foreach ($insp_results as $insp_result) {

					if(!empty($insp_result['image'])){
						$insp_data['insp_images'][] = $insp_result['image'];

					}else{
						$insp_data=array();

					}
				}

				
				//Weight data

			

				if($result['quantity']!=0){
				
					/* Ankur Mittal Exitstin Product Weight Selection */
					$key = array_search($result['product_id'], array_column($orderProduct, 'product_id'));
					
					$wresult=$this->model_account_membership->getProductweight($result['product_id']);
					$dresult=$this->model_account_membership->getlistdelivery($userId);	
					if(is_numeric($key)){		
											
						$wkey 			= 	array_search($orderProduct[$key]['weight_id'], array_column($wresult, 'id'));					
						
						if(is_numeric($wkey)){
							$weight				=	$weight+$wresult[$wkey]['value'];
						}else{
							$selectedweightId = '';
							$selecteddeliveryId = '';
						}
						$selectedweightId 	= 	$orderProduct[$key]['weight_id'];
						$selecteddeliveryId = 	$orderProduct[$key]['delivery_id'];

						$productkey		=	array_search($result['product_id'], array_column($orderProduct, 'product_id'));					
						$chked='checked="checked"';

						$pweight[]=array(
							'product_id'=>$result['product_id'],
							'weight'=>$wresult[$wkey]['value']
						);

					}else{
						$selectedweightId = '';
						$selecteddeliveryId = '';
						$chked='';
					}
					
			
				$data['products'][] = array(

					'product_id'  => $result['product_id'],

					'thumb'       => $image,

					'name'        => $result['name'],

					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',

					'price'       => isset($price)?$price:0.00,
				     // Add images Data 
					 'image' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height')),

					'insp_images' => !empty($insp_data['insp_images'][0]['popup'])?$insp_data['insp_images'][0]['popup']:'',

					//End
					'wresult'=>$wresult,
					'dresult'=>$dresult,
					'selected_weigth_id'=>$selectedweightId,
					'selected_slot_id'=>$selecteddeliveryId,
					'chked'=>$chked,
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url),


				);
			}

		}
		//echo '<pre>';
		//print_r($data['products']);
		//die;

		$totalweight=$this->model_account_membership->getCustomerWeightAllowded($userId);
	
		$timeslot=	$count=$this->model_account_membership->getTimeslot();

		$dsetting=	$count=$this->model_account_membership->getlistdelivery($userId);

		$data['timeslot'] = $timeslot;
		$data['dsetting'] = $dsetting;
		$data['dresult']=$dresult;
		$data['total']=$weight/1000;
		$data['totalweight'] = $totalweight;
		$data['selectedProduct']=$orderProduct;
		$data['pweight']=json_encode($pweight);
		$data['kgweight'] =number_format($totalweight/1000,2);
		$data['backlink'] = $this->url->link('account/deliverysettings', '', true);
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('membership/edit_membership', $data));

	}
	public function add() {
		
		$json = array();
		if (!$this->customer->isLogged()) {
			$json['error']='sessiion_expire';
		}
		$this->load->model('account/membership');	
		$userId=$this->session->data['customer_id'];
		if (isset($_POST)) {
			$_POST['customer_id'] = $this->session->data['customer_id'];
			$_POST['dday']= array_filter($_POST['dday']);
			$_POST['productId']= array_filter($_POST['productId']);
			$_POST['weight']= array_filter($_POST['weight']);
			$_POST['vslot']= array_filter($_POST['vslot']);
			//print_r($_POST['dday']);
			//print_r($_POST['productId']);
			//print_r($_POST['vslot']);
			$monday = date('Y-m-d', strtotime( 'monday this week' ) );
			$customerExist  = array();
			$customerExist['week_date'] = $monday;	
			$customerExist['customer_id'] = $this->session->data['customer_id'];
			
			$weight_allowded = $this->model_account_membership->getVegOrderWeight($_POST['weight'],$_POST['customer_id']);

			$customerexist = $this->model_account_membership->checkCustomerFilledVegOrder($customerExist);

			if($weight_allowded && $customerexist){
				$dayarray = array();
				$day_filter = array_filter($_POST['dday']);
				foreach($day_filter as $key=>$day){
					$dayarray[$day][] = array('product_id'=>$_POST['productId'][$key],'weight'=>$_POST['weight'][$key]);
				}
				
				foreach($dayarray as $key=>$daydata){
					$data = array();
					$data['customer_id'] = $this->session->data['customer_id'];
					$data['delivery_id'] = $key;
					$data['slot_id'] = $_POST['vslot'][$key][0];
					$data['status'] = 1;
					$data['week_date'] = $monday;				
					$addvegorders	=	$this->model_account_membership->addVegOrders($data,$dayarray[$key]);				
				}			
				$json['status']='success';	
				$json['message']='success';	
				$json['redirect']=$this->url->link('account/membership/ordersuccess', '', true);
			}else if($weight_allowded){
				$json['status']='error';	
				$json['message']='You Have already Placed Order for this week!!';
			}else if($customerexist){
				$json['status']='error';	
				$json['message']='Allocated Weight is greater than Assigned Weight';
			}else{
				$json['status']='error';	
				$json['message']='Allocated Weight is greater than Assigned Weight OR Already Placed Order for this week.';
			}
		}else{
			$json['status']='error';
			$json['message']='We got some error, Please try again.';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function editSave() {
		
		$json = array();
		if (!$this->customer->isLogged()) {
			$json['error']='sessiion_expire';
		}
		$this->load->model('account/membership');	
		$userId=$this->session->data['customer_id'];
		if (isset($_POST)) {
			$_POST['customer_id'] = $this->session->data['customer_id'];
			$_POST['dday']= array_filter($_POST['dday']);
			$_POST['productId']= array_filter($_POST['productId']);
			$_POST['weight']= array_filter($_POST['weight']);
			$_POST['vslot']= array_filter($_POST['vslot']);
			//print_r($_POST['dday']);
			//print_r($_POST['productId']);
			//print_r($_POST['vslot']);
			$monday = date('Y-m-d', strtotime( 'monday this week' ) );
			$customerExist  = array();
			$customerExist['week_date'] = $monday;	
			$customerExist['customer_id'] = $this->session->data['customer_id'];
			
			$weight_allowded = $this->model_account_membership->getVegOrderWeight($_POST['weight'],$_POST['customer_id']);

			$customerexist = $this->model_account_membership->checkCustomerFilledVegOrder($customerExist);
			$data=array(
				'customer_id'=>$this->session->data['customer_id']
			);
			

			if($weight_allowded){
				$dayarray = array();
				$day_filter = array_filter($_POST['dday']);
				foreach($day_filter as $key=>$day){
					$dayarray[$day][] = array('product_id'=>$_POST['productId'][$key],'weight'=>$_POST['weight'][$key]);
				}
				$this->model_account_membership->deleteOrderLineItems($data);
				foreach($dayarray as $key=>$daydata){
					$data = array();
					$data['customer_id'] = $this->session->data['customer_id'];
					$data['delivery_id'] = $key;
					$data['slot_id'] = $_POST['vslot'][$key][0];
					$data['status'] = 1;
					$data['week_date'] = $monday;				
					$addvegorders	=	$this->model_account_membership->addVegOrders($data,$dayarray[$key]);				
				}			
				$json['status']='success';	
				$json['message']='success';	
				$json['redirect']=$this->url->link('account/membership/ordersuccess', '', true);
			}else if($weight_allowded){
				$json['status']='error';	
				$json['message']='You Have already Placed Order for this week!!';
			}else if($customerexist){
				$json['status']='error';	
				$json['message']='Allocated Weight is greater than Assigned Weight';
			}else{
				$json['status']='error';	
				$json['message']='Allocated Weight is greater than Assigned Weight OR Already Placed Order for this week.';
			}
		}else{
			$json['status']='error';
			$json['message']='We got some error, Please try again.';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function ordersuccess(){
		
		if (!$this->customer->isLogged()) {
		
			$this->response->redirect($this->url->link('account/login', '', true));

		}

		$this->load->language('account/membership');

		$this->load->model('account/membership');
		$userId=$this->session->data['customer_id'];
		$this->document->setTitle($this->language->get('heading_title'));
		$count=$this->model_account_membership->getselectedday($userId);

		if($count==0){
			$this->response->redirect($this->url->link('account/deliverysettings', '', true));
			exit;
		}
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')

		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		if (!isset($this->session->data['customer_id'])) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}



		$userId=$this->session->data['customer_id'];

		$results = $this->model_account_membership->getcurrentVegOrderDetails($userId);



		$sum=0;
		$fd=date("w") + 2;
		$flag=0;

		foreach ($results as $result) {
				$sum=$sum+$result['wgt'];
				$data['customers'][] = array(

				'veg_order_id' => $result['veg_order_id'],

				'product_id' => $result['product_id'],

				'slotname' => $result['slotname'],

				'weight_id' => $result['weight_id'],

				'delivery_id' => $result['delivery_id'],
				'dname' => $result['dname'],

				'model' => $result['model'],

					'view_link' => $this->url->link('account/membership/vieworder', '&orderId=' . $result['veg_order_id'],true),	
				'name' => $result['name'],
				'totalweight' => $result['total_weight']/1000,

				'weight_value' => $result['weight_value'],

				'date_added' => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
				);
				$data['wgt']=$sum;
				if($result['delivery_id']>=$fd){
						$flag++;

				}

		}

		if($flag==count($results)){
			$data['edit_link']=$this->url->link('account/deliverysettings/editOrder');
		}else{
			$data['edit_link']='';
		}


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('membership/ordersuccess', $data));
	
	}


	public function vieworder(){
	
	 if (!$this->customer->isLogged()) {
		
			$this->response->redirect($this->url->link('account/login', '', true));

		}
		if(!$this->request->get['orderId']){
			$this->response->redirect($this->url->link('account/deliverysettings', '', true));

		}
		$orderId=$this->request->get['orderId'];
		$this->load->model('account/membership');

		if($orderId){
			$results = $this->model_account_membership->getcurrentVegOrderitem($orderId);

		//print_r($results);exit;
				$sum=0;
			foreach ($results as $result) {
					$sum=$sum+$result['wgt'];
					$data['customers'][] = array(

					'veg_order_id' => $result['veg_order_id'],

					'product_id' => $result['product_id'],

					'slotname' => $result['slotname'],

					'weight_id' => $result['weight_id'],

					'delivery_id' => $result['delivery_id'],
					'dname' => $result['dname'],

					'model' => $result['model'],

						'view_link' => $this->url->link('account/membership/vieworder', '&orderId=' . $result['veg_order_id'],true),	
					'name' => $result['name'],
					'totalweight' => $result['total_weight']/1000,

					'weight_value' => $result['weight_value'],

					'date_added' => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
					);
				

			}
			$data['wgt']=$sum/1000;
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$this->response->setOutput($this->load->view('membership/vorderitem', $data));
		}

	}


	public function edit() {

		if (!$this->customer->isLogged()) {
		
			$this->response->redirect($this->url->link('account/login', '', true));

		}

		$this->load->language('account/membership');

		$this->load->model('account/membership');
		$this->load->model('membership/subscriptions');


		$day=$this->model_membership_subscriptions->getenabbleMembership();
	
		$currentDay=date('l');
		$isAllow=1;
		foreach ($day as $key => $value) {
				if($value['name']===$currentDay){
						$isAllow=2;

				}
		}		

		if($isAllow==1){
			$this->response->redirect($this->url->link('account/subscriptions', '', true));

		}

		
		$userId=$this->session->data['customer_id'];
		$this->document->setTitle($this->language->get('heading_title'));
		$count=$this->model_account_membership->getselectedday($userId);

		if($count==0){
			$this->response->redirect($this->url->link('account/deliverysettings', '', true));
			exit;
		}
	}


}