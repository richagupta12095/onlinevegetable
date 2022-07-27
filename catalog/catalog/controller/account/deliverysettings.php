<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class ControllerAccountDeliverysettings extends Controller {

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

		if($isAllow=='rs'){
			$this->response->redirect($this->url->link('account/subscriptions', '', true));
		}



		$userId=$this->session->data['customer_id'];
		

		$monday = date('Y-m-d', strtotime( 'monday this week' ) );
		$customerExist  = array();
		$customerExist['week_date'] = $monday;	
		$customerExist['customer_id'] = $userId;
		$weekorder = $this->model_account_membership->checkweekorderexsit($customerExist);
	
		//$dsettings=$this->model_account_membership->deldsettings($userId);
		
		
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_home'),

			'href' => $this->url->link('common/home')

		);
		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_account'),

			'href' => $this->url->link('account/account', '', true)

		);
		$userId=$this->session->data['customer_id'];
		//$userdsetting=$this->model_account_membership->getdsetting($userId);
		$ar=[];
		foreach ($userdsetting as $key => $value) {
			$ar[]=$value['delivery_settings_id'];
		}
		
		$this->document->setTitle($this->language->get('delivery_heading_title'));

	 	$dsettings=$this->model_account_membership->getdeliverysetting();
	 	foreach ($dsettings as $key => $value) {
	 		 if(in_array($value['id'],$ar)){
	 		 	$checked='1';
	 		 }else{
	 		 	$checked='';
	 		 }
	 		 $dsettings[$key]['checked']=$checked;
	 	}
	 	$d= date('l');

	 	foreach ($dsettings as $key => $value) {
	 		if($value['name']===$d){
	 			$i=$key;

	 		}
	 		# code...
	 	}
	 	$dsettings=array_slice($dsettings,$i);
	 	if(empty($dsettings)){
	 		//$this->response->redirect($this->url->link('account/subscriptions', '', true));
	 		//exit();
	 	}
	 	$data['setting']=$dsettings;
	 	$data['udsetting']=$userdsetting;
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
			$editButtonresults = $this->model_account_membership->getEditButtonEnabledDetails($userId);	
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

				if($result['delivery_id']>=$fd){
					$flag++;

				}

			}

			$data['wgt']=$sum;

			if(!empty($editButtonresults)){
				$data['edit_link']=$this->url->link('account/deliverysettings/editOrder');
			}else{
				$data['edit_link']='';
			}

			$this->response->setOutput($this->load->view('membership/my_membership_order', $data));
		}else{
			$this->response->setOutput($this->load->view('membership/deliverysettings', $data));

		}
	}

	public function add() {
		
		$json = array();
		if (!$this->customer->isLogged()) {
			$json['error']='sessiion_expire';
		}
			$this->load->model('account/membership');
		$userId=$this->session->data['customer_id'];
		if (isset($_POST)) {
			 $dsettings=$this->model_account_membership->deldsettings($userId);
		 
			foreach ($_POST['dday'] as $key => $value) {
				$res=$this->model_account_membership->saveliverysetting($userId,$value);
			}
			$json['error']='success';
			$json['redirect']=$this->url->link('account/membership', '', true);
		}else{
			$json['error']='data_issue';
		}

		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function saveEditsetting() {
		
		$json = array();
		if (!$this->customer->isLogged()) {
			$json['error']='sessiion_expire';
		}
			$this->load->model('account/membership');
		$userId=$this->session->data['customer_id'];
		if (isset($_POST)) {
		 	$dsettings=$this->model_account_membership->deldsettings($userId);


		 
			foreach ($_POST['dday'] as $key => $value) {
				$res=$this->model_account_membership->saveliverysetting($userId,$value);
			}
			$json['error']='success';
			$json['redirect']=$this->url->link('account/membership/editmembership', '', true);
		}else{
			$json['error']='data_issue';
		}

		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function editOrder(){
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$this->load->language('account/membership');
		$this->load->model('account/membership');
		$this->load->model('membership/subscriptions');
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

	


		$userId=$this->session->data['customer_id'];
		

		$monday = date('Y-m-d', strtotime( 'monday this week' ) );
		$customerExist  = array();
		$customerExist['week_date'] = $monday;	
		$customerExist['customer_id'] = $userId;
		$weekorder = $this->model_account_membership->checkweekorderexsit($customerExist);
	
		//$dsettings=$this->model_account_membership->deldsettings($userId);
		
		
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_home'),

			'href' => $this->url->link('common/home')

		);
		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_account'),

			'href' => $this->url->link('account/account', '', true)

		);
		$userId=$this->session->data['customer_id'];
		$userdsetting=$this->model_account_membership->getdsetting($userId);

	
		$ar=[];
		foreach ($userdsetting as $key => $value) {
			$ar[]=$value['delivery_settings_id'];
		}
		$this->document->setTitle($this->language->get('delivery_heading_title'));
	 	$dsettings=$this->model_account_membership->getdeliverysetting();

	 	foreach ($dsettings as $key => $value) {
	 		 if(in_array($value['id'],$ar)){
	 		 	$checked='1';
	 		 }else{
	 		 	$checked='';
	 		 }
	 		 $dsettings[$key]['checked']=$checked;
	 	}
	 	$d= date('l');
		foreach ($dsettings as $key => $value) {
	 		if($value['name']===$d){
	 			$i=$key;
	 		}
	 		# code...
	 	}
	 	$dsettings=array_slice($dsettings,$i);
	
	 	$data['setting']=$dsettings;
	 	$data['udsetting']=$userdsetting;
	 	$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('membership/edit_deliverysettings', $data));

	}

}