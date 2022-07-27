<?php
class Controllerewalletewallet extends Controller {
	private $error = array();
	public function index() {
		$this->transaction();
	}
	public function add() {
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validation()) {
			 
			$this->addtransaction($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet', 'user_token=' . $this->session->data['user_token'].$url, 'SSL'));
		}
		$this->addtransaction_form();
	}
	private function validation(){

		if(!$this->request->post['customer_id']){
			$this->error['name']="Select Name";
		}else if(!$this->request->post['amount']){
			$this->error['amount']="Enter Amount";
		}else if(!$this->request->post['description'] ){
			$this->error['description']="Enter Discription";
		}
		return !$this->error;
	}
	public function delete() {
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $transaction_id) {
				$this->deletetransaction($transaction_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet', 'user_token=' . $this->session->data['user_token'].$url, 'SSL'));
		}
		$this->transaction();
	}
	public function deleterequest() {
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $request_id) {
				$this->deleterequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'].$url, 'SSL'));
		}
		$this->cod_request();
	}
	public function addtransaction_form() {
		$this->load->language('e_wallet/e_wallet');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = !isset($this->request->get['transaction_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['entry_customer_id'] = $this->language->get('entry_customer_id');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_description'] = $this->language->get('entry_description');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
			$data['error'] = "";
		if (isset($this->error['description'])) {
			$data['error'] = $this->error['description'];
		}  if (isset($this->error['amount'])) {
			$data['error'] = $this->error['amount'];
		} if (isset($this->error['name'])) {
			$data['error'] = $this->error['name'];
		}
		
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('e_wallet/e_wallet', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);
		$data['action'] = $this->url->link('e_wallet/e_wallet/add', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('e_wallet/e_wallet', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['user_token'] = $this->session->data['user_token'];
		$json=array();
		if(isset($this->request->get['order_id'])){
			$this->load->model("sale/order");

			$order_info=$this->model_sale_order->getOrder($this->request->get['order_id']);
			$order_total=$this->model_sale_order->getOrderTotals($this->request->get['order_id']);
			$temp=0;
			$amount=0;
			foreach ($order_total as $key => $value) {
				if($value['code'] == "e_wallet_total"){
					$temp++;
					$amount=abs($value['value']);
				}
			}
		 	if($temp && $amount){
		 		$json=array(
		 			"amount" => $amount,
		 			"msg" => "Refund Amount For Order, Order Id is: #".$this->request->get['order_id'],
		 			"customer_id" => $order_info['customer_id'],
		 			"name" => $order_info['firstname'].' '.$order_info['lastname'].' - '.$order_info['email'],		 			
		 		);
		 	}
		}
		
		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif ($json) {
			$data['description'] = $json['msg'];			
		} else {
			$data['description'] = '';
		}
		
		if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif ($json) {
			$data['customer_id'] = $json['customer_id'];			
		} else {
			$data['customer_id'] = '';
		}
		if (isset($this->request->post['customer'])) {
			$data['customer'] = $this->request->post['customer'];
		} elseif ($json) {
			$data['customer'] = $json['name'];			
		} else {
			$data['customer'] = '';
		}
		
		if (isset($this->request->post['amount'])) {
			$data['amount'] = $this->request->post['amount'];
		}elseif ($json) {
			$data['amount'] = $json['amount'];			
		}  else {
			$data['amount'] = '';
		}
		
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE status = 1");
		$customer = array();
		foreach ($query->rows as $value) {
			$customer[] = array(
				'customer_id' => $value['customer_id'], 
				'name' => $value['firstname'].' '.$value['lastname'], 
				'email' => $value['email'], 
			); 
		}
		$data['customers']=json_encode($customer);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('e_wallet/e_wallet_form', $data));
	}
	public function customers() {
		$data = array_merge($this->load->language('e_wallet/e_wallet'),array());
		$page = 1;
		$limit = $this->config->get('config_limit_admin');
		$url = $data['email'] = '';
		$where2 = $where = ' WHERE 1 ';
		$data['datefrom'] = '';
		$data['dateto'] = '';
		$per = DB_PREFIX;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		if(isset($this->request->request['email']) && $this->request->request['email']){
			$url ='&email='.$this->request->request['email'];
			$data['email'] = $this->request->request['email'];
			$where .= " AND (customer_id IN (SELECT customer_id FROM  `{$per}customer` WHERE `email` like '%{$data['email']}%' or `telephone` like '%{$data['email']}%' OR firstname like '%{$data['email']}%' OR lastname like '%{$data['email']}%') OR customer_id like '%{$data['email']}%') ";
			$where2 .= " AND (c.email like '%{$data['email']}%' or c.telephone like '%{$data['email']}%' OR c.customer_id like '%{$data['email']}%' OR c.firstname like '%{$data['email']}%' OR c.lastname like '%{$data['email']}%') ";
		}
		$str = "SELECT SUM(price) AS balance FROM  `{$per}e_wallet_transaction`";
		$data['totalbalance'] = $this->db->query($str)->row['balance'];
		$data['totallabance_format'] = $this->currency->format($data['totalbalance'],$this->config->get('config_currency'));
		if( !$data['totalbalance']){
			$data['totalbalance']=1;
		}
		$str = "SELECT COUNT(*) AS total FROM (SELECT customer_id FROM {$per}e_wallet_bank
		UNION
		SELECT customer_id FROM {$per}e_wallet_transaction ) T
		{$where} ";
		$totalcustomers = $this->db->query($str)->row['total'];
		$str = "SELECT c.firstname,c.lastname,c.email,b.*,c.customer_id,
			(SELECT SUM(t.price) FROM {$per}e_wallet_transaction t WHERE c.customer_id = t.customer_id) AS balance
			FROM {$per}customer c
			LEFT JOIN {$per}e_wallet_bank b ON (b.customer_id = c.customer_id)			
			{$where2} 		
			ORDER BY balance DESC
			LIMIT ".$filter['start'] .' , '.$filter['limit'];
			  
		$results = $this->db->query($str)->rows;

		$data['customers'] = array();
		$this->load->model('customer/customer');
		foreach ($results as $result) {
			$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
			$data['customers'][] = array(
				'customer_id'  => $result['customer_id'],
				'customer' => $customer_info['firstname']." ".$customer_info['lastname'],
				'email' => $customer_info['email'],
				'bank_name' => $result['bank_name'],
				'branch_code' => $result['branch_code'],
				'swift' => $result['swift'],
				'ifsc' => $result['ifsc'],
				'ac_name' => $result['ac_name'],
				'ac_no' => $result['ac_no'],
				'balance' => $this->currency->format((float)$result['balance'],$this->config->get('config_currency')),
				'per' => round(((float)$result['balance'] * 100) / $data['totalbalance'],2),
				'c_link' => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'], 'SSL')
			);
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('e_wallet/e_wallet/customers', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);
		$data['formurl'] = $this->url->link('e_wallet/e_wallet/customers', 'user_token=' . $this->session->data['user_token'], 'SSL');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$data['sort_name'] = $this->url->link('e_wallet/e_wallet/customers', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['sort_sort_order'] = $this->url->link('e_wallet/e_wallet/customers', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$pagination = new Pagination();
		$pagination->total = $totalcustomers;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('e_wallet/e_wallet/customers', 'user_token=' . $this->session->data['user_token']. '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totalcustomers) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totalcustomers - $this->config->get('config_limit_admin'))) ? $totalcustomers : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totalcustomers, ceil($totalcustomers / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('e_wallet/customers_list', $data));
	}
	public function transaction() {
		$this->load->language('e_wallet/e_wallet');
		$page = 1;
		$limit = $this->config->get('config_limit_admin');
		$url = $data['email'] = '';
		$where = ' WHERE 1 ';
		$data['datefrom'] = '';
		$data['dateto'] = '';
		$per = DB_PREFIX;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		if(isset($this->request->request['datefrom']) && $this->request->request['datefrom']){
			$url ='&datefrom='.$this->request->request['datefrom'];
			$datefrom = date('Y-m-d',strtotime($this->request->request['datefrom']));
			$where .= " AND date_added >= '".date('Y-m-d',strtotime($datefrom))."' ";
			$data['datefrom'] = $this->request->request['datefrom'];
		}
		if(isset($this->request->request['dateto']) && $this->request->request['dateto']){
			$url ='&dateto='.$this->request->request['dateto'];
			$dateto = date('Y-m-d',strtotime($this->request->request['dateto']));
			$where .= " AND date_added <= '".date('Y-m-d',strtotime($dateto.' +1 days'))."' ";
			$data['dateto'] = $this->request->request['dateto'];
		}
		 
		if(isset($this->request->request['email']) && $this->request->request['email']){
			$url ='&email='.$this->request->request['email'];
			$data['email'] = $this->request->request['email'];
			$where .= " AND customer_id IN (SELECT customer_id FROM  `{$per}customer` WHERE `email` like '%{$data['email']}%' or `telephone` like '%{$data['email']}%') ";
		}
		$str = "SELECT COUNT(*) AS total FROM `{$per}e_wallet_transaction` {$where}";
		$totaltransaction = $this->db->query($str)->row['total'];
		$str = "SELECT * FROM `{$per}e_wallet_transaction` {$where} ORDER BY date_added DESC LIMIT ".$filter['start'].",".$filter['limit']." ";
		$results = $this->db->query($str)->rows;
		$data['transactions'] = array();
		$this->load->model('customer/customer');
		foreach ($results as $result) {
			$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
			$data['transactions'][] = array(
				'transaction_id'  => $result['transaction_id'],
				'customer_id'       => $result['customer_id'],
				'description' => $result['description'],
				'customer' => @$customer_info['email'].' - '.@$customer_info['telephone'],
				'price' => $this->currency->format($result['price'],$this->config->get('config_currency')),
				'o_price' => $result['price'],
				'date' => date('d-m-Y h:i:s A',strtotime($result['date_added'])),
				'c_link' => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'], 'SSL')
			);
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('e_wallet/e_wallet', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);
		$data['add'] = $this->url->link('e_wallet/e_wallet/add', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['delete'] = $this->url->link('e_wallet/e_wallet/delete', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['formurl'] = $this->url->link('e_wallet/e_wallet/transaction', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_date'] = $this->language->get('column_date');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$data['sort_name'] = $this->url->link('e_wallet/e_wallet', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['sort_sort_order'] = $this->url->link('e_wallet/e_wallet', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$pagination = new Pagination();
		$pagination->total = $totaltransaction;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('e_wallet/e_wallet', 'user_token=' . $this->session->data['user_token']. '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totaltransaction) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totaltransaction - $this->config->get('config_limit_admin'))) ? $totaltransaction : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totaltransaction, ceil($totaltransaction / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('e_wallet/e_wallet_list', $data));
	}
	public function approverequest() {
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $request_id) {
				$this->approverequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'].$url, 'SSL'));
		}
		$this->cod_request();
	}
	public function rejectrequest() {
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $request_id) {
				$this->rejectrequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'].$url, 'SSL'));
		}
		$this->cod_request();
	}
	public function add_request() {
		$page = 1;
		$data['status'] = '';
		$limit = $this->config->get('config_limit_admin');
		$url = $data['email'] = '';
		$where = ' WHERE 1 ';
		$data['datefrom'] = '';
		$data['dateto'] = '';
		$per = DB_PREFIX;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		if(isset($this->request->request['datefrom']) && $this->request->request['datefrom']){
			$url ='&datefrom='.$this->request->request['datefrom'];
			$data['datefrom'] = $this->request->request['datefrom'];
			$where .= " AND date_added >= '".date('Y-m-d',strtotime(date('Y-m-d',strtotime($this->request->request['datefrom']))))."' ";
		}
		if(isset($this->request->request['dateto']) && $this->request->request['dateto']){
			$url ='&dateto='.$this->request->request['dateto'];
			$data['dateto'] = $this->request->request['dateto'];
			$where .= " AND date_added <= '".date('Y-m-d',strtotime(date('Y-m-d',strtotime($this->request->request['dateto'])).' +1 days'))."' ";
		}
		if(isset($this->request->request['status']) && trim($this->request->request['status']) != ''){
			$url ='&status='.$this->request->request['status'];
			$data['status'] = $this->request->request['status'];
			$where .= " AND status = '".(int)$data['status']."' ";
		}
		if(isset($this->request->request['email']) && $this->request->request['email']){
			$url ='&email='.$this->request->request['email'];
			$data['email'] = $this->request->request['email'];
			$where .= " AND customer_id IN (SELECT customer_id FROM  `{$per}customer` WHERE `email` like '%{$data['email']}%' or `telephone` like '%{$data['email']}%') ";
		}
		$totaltransaction = $this->db->query("SELECT COUNT(*) AS total FROM {$per}cod_request {$where}");
		$totaltransaction = $totaltransaction->row['total'];
		$results = $this->db->query("SELECT * FROM {$per}cod_request {$where} ORDER BY date_added DESC LIMIT ".$filter['start'].",".$filter['limit']." ");
		$results = $results->rows;
		$data['requests'] = array();
		$this->load->model('customer/customer');
		foreach ($results as $result) {
			$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
			$data['requests'][] = array(
				'request_id'  => $result['request_id'],
				'customer_id' => $result['customer_id'],
				'description' => $result['description'],
				'status' => $result['status'],
				'customer' => $customer_info['email'].' - '.$customer_info['telephone'],
				'price' => $this->currency->format($result['amount'],$this->config->get('config_currency')),
				'date' => date('d-m-Y h:i:s A',strtotime($result['date_added'])),
				'c_link'       => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'], 'SSL')
			);
		}
		$this->load->language('e_wallet/e_wallet');
		$data['breadcrumbs'] = array( array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
			), array(
				'text' => $this->language->get('request_heading_title'),
				'href' => $this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'], 'SSL')
			)
		);
		$data['delete'] = $this->url->link('e_wallet/e_wallet/deleterequest', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['approveurl'] = $this->url->link('e_wallet/e_wallet/approverequest', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['rejecturl'] = $this->url->link('e_wallet/e_wallet/rejectrequest', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['formurl'] = $this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['dis_edit'] = $this->url->link('e_wallet/e_wallet/dis_edit', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['heading_title'] = $this->language->get('request_heading_title');
		$data['text_list'] = $this->language->get('request_text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_date'] = $this->language->get('column_date');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$data['sort_name'] = $this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['sort_sort_order'] = $this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$pagination = new Pagination();
		$pagination->total = $totaltransaction;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token']. '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totaltransaction) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totaltransaction - $this->config->get('config_limit_admin'))) ? $totaltransaction : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totaltransaction, ceil($totaltransaction / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('e_wallet/cod_request_list', $data));
	}
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'e_wallet/e_wallet')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	public function deletetransaction($id){
		$str = "DELETE FROM `" . DB_PREFIX . "e_wallet_transaction` WHERE transaction_id = '".(int)$id."'";
		return $this->db->query($str);
	}
	public function deleterequests($id){
		$str = "DELETE FROM `" . DB_PREFIX . "cod_request` WHERE request_id = '".(int)$id."'";
		return $this->db->query($str);
	}
	public function approverequests($id){
		$per = DB_PREFIX;
		$sql = "SELECT * FROM `{$per}cod_request` WHERE request_id = '".(int)$id."' AND status = '0'";
		$request = $this->db->query($sql)->row;
		if($request){
			$ststus = 'Approved';
			$adddata = array(
				'customer_id' => $request['customer_id'],
				'amount' => $request['amount'],
				'description' => 'Request Approved : '.$request['description'].'.'
			);
			$desc="Request Approved : ".$request['description'];
			$transaction_id = $this->addtransaction($adddata);
			$str = "UPDATE `" . DB_PREFIX . "cod_request` SET description='".$this->db->escape($desc)."', status = '1',transaction_id = '{$transaction_id}' WHERE request_id = '".(int)$id."'";
			 
			$this->db->query($str);
			$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  customer_id = {$request['customer_id']}")->row;
			if($c_info){
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$subject = $this->config->get('e_wallet_title')." Request ".$ststus.".";
				$message = 'Your Request '.$ststus." From Admin \n".
					" Description : ".$request['description']." \n ".
					" Amount : ".$request['amount']." \n ".
					" Date : ".date('d-m-Y h:i:s A')." \n ";
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				$mail->setTo($this->config->get('config_email'));
				$mail->send();
				$mail->setTo($c_info['email']);
				$mail->send();
			}
			return true;
		}
		return false;
	}
	public function rejectrequests($id){
		$per = DB_PREFIX;
		$sql = "SELECT * FROM `{$per}cod_request` WHERE request_id = '".(int)$id."' AND status = '0'";
		$request = $this->db->query($sql)->row;
		if($request){
			$adddata = array(
				'customer_id' => $request['customer_id'],
				'amount' => 0,
				'description' => 'Request Rejected : '.$request['description'].'.'
			);
			$transaction_id =$this->addtransaction($adddata);
			$desc="Request Rejected : ".$request['description'];			
			$str = "UPDATE `" . DB_PREFIX . "cod_request` SET description='".$this->db->escape($desc)."', status = '2',transaction_id = '{$transaction_id}' WHERE request_id = '".(int)$id."'";
			$this->db->query($str);
			$ststus = 'Rejected';
			$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  customer_id = {$request['customer_id']}")->row;
			if($c_info){
				$subject = $this->config->get('e_wallet_title')." Request ".$ststus.".";
				$message = 'Your Request '.$ststus." From Admin \n".
					" Description : ".$request['description']." \n ".
					" Amount : ".$request['amount']." \n ".
					" Date : ".date('d-m-Y h:i:s A')." \n ";
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				$mail->setTo($c_info['email']);
				$mail->send();
			}
			return true;
		}
		return false;
	}
	public function dis_edit(){
		$str = "UPDATE `" . DB_PREFIX . "cod_request` SET description = '".$this->db->escape($this->request->post['description'])."' WHERE request_id = '".(int)$this->request->post['request_id']."'";
		$this->db->query($str);
		$str = "SELECT * FROM  `".DB_PREFIX."cod_request` WHERE request_id = '".(int)$this->request->post['request_id']."'";
		$request = $this->db->query($str)->row;
		$text = $this->db->escape($this->request->post['description']);
		$str = "UPDATE  `".DB_PREFIX."e_wallet_transaction` SET description =  '{$text}' WHERE transaction_id = '".$request['transaction_id']."'";
		 
		$this->db->query($str);
	}
	public function addtransaction($data = array()){

		$customer_id = (int)$data['customer_id'];
		$str = "INSERT INTO `" . DB_PREFIX . "e_wallet_transaction` SET			
			`customer_id` = '".(int)$customer_id."',
			`price` = '".(float)$data['amount']."',
			`description` = '".$this->db->escape($data['description'])."',
			`date_added` = NOW()";
		$this->db->query($str);
		$transaction_id = $this->db->getLastId();
		$balance = (float)$this->getBalance($data);
		$str = "UPDATE `".DB_PREFIX."e_wallet_transaction` SET
			balance = {$balance}
			WHERE customer_id = ".$customer_id." AND transaction_id = ".(int)$transaction_id;
		$this->db->query($str);
		return $transaction_id;
	}
	public function getBalance($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		$str = "SELECT SUM(price) as total FROM `".DB_PREFIX."e_wallet_transaction` WHERE customer_id = ".$customer_id;
		$data = $this->db->query($str);
		return $data->row['total'];
	}
	public function checkrefund(){
		$json=array();
		if(isset($this->request->get['order_id'])){			 
			$this->load->model("sale/order");
			$order_info=$this->model_sale_order->getOrder($this->request->get['order_id']);
			
			if($this->config->get('e_wallet_status') && $this->request->get['o_his'] == $this->config->get('e_wallet_refund_order_id') && $order_info['payment_code'] != 'e_wallet_payment'){
				$order_total=$this->model_sale_order->getOrderTotals($this->request->get['order_id']);
				$temp=0;
				$amount=0;

				foreach ($order_total as $key => $value) {
					if($value['code'] == "e_wallet_total"){
						$temp++;
						$amount=abs($value['value']);
					}
				}
			 	if($temp && $amount){
			 		$json['success']=array(
			 			"amount" => $amount,
			 			"msg" => "Refund Amount For Order, Order Id is: #".$this->request->get['order_id'],
			 			"customer_id" => $order_info['customer_id'],
			 			"firstname" => $order_info['firstname'],
			 			"lastname" => $order_info['lastname'],
			 			"email" => $order_info['email'],
			 			"link" => html_entity_decode($this->url->link('e_wallet/e_wallet/add', 'user_token=' . $this->session->data['user_token'].'&order_id='.$this->request->get['order_id'], 'SSL'), ENT_QUOTES, 'UTF-8'),
			 		);
			 	}				
			}
		}
		$this->response->addHeader('Content-Type: application/json');
   		$this->response->setOutput(json_encode($json));
	}
	public function wdis_edit(){
		$str = "UPDATE `" . DB_PREFIX . "withdraw_request` SET description = '".$this->db->escape($this->request->post['description'])."' WHERE request_id = '".(int)$this->request->post['request_id']."'";
		$this->db->query($str);
		$str = "SELECT * FROM  `".DB_PREFIX."withdraw_request` WHERE request_id = '".(int)$this->request->post['request_id']."'";
		$request = $this->db->query($str)->row;
		$text = $this->db->escape($this->request->post['description']);
		$str = "UPDATE  `".DB_PREFIX."e_wallet_transaction` SET description =  '{$text}' WHERE transaction_id = '".$request['transaction_id']."'";
		 
		$this->db->query($str);
	}
	public function withdraw_request() {
		$page = 1;
		$data['status'] = '';
		$limit = $this->config->get('config_limit_admin');
		$url = $data['email'] = '';
		$where = ' WHERE 1 ';
		$data['datefrom'] = '';
		$data['dateto'] = '';
		$per = DB_PREFIX;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		if(isset($this->request->request['datefrom']) && $this->request->request['datefrom']){
			$url ='&datefrom='.$this->request->request['datefrom'];
			$data['datefrom'] = $this->request->request['datefrom'];
			$where .= " AND date_added >= '".date('Y-m-d',strtotime(date('Y-m-d',strtotime($this->request->request['datefrom']))))."' ";
		}
		if(isset($this->request->request['dateto']) && $this->request->request['dateto']){
			$url ='&dateto='.$this->request->request['dateto'];
			$data['dateto'] = $this->request->request['dateto'];
			$where .= " AND date_added <= '".date('Y-m-d',strtotime(date('Y-m-d',strtotime($this->request->request['dateto'])).' +1 days'))."' ";
		}
		if(isset($this->request->request['status']) && trim($this->request->request['status']) != ''){
			$url ='&status='.$this->request->request['status'];
			$data['status'] = $this->request->request['status'];
			$where .= " AND status = '".(int)$data['status']."' ";
		}
		if(isset($this->request->request['email']) && $this->request->request['email']){
			$url ='&email='.$this->request->request['email'];
			$data['email'] = $this->request->request['email'];
			$where .= " AND customer_id IN (SELECT customer_id FROM  `{$per}customer` WHERE `email` like '%{$data['email']}%' or `telephone` like '%{$data['email']}%') ";
		}
		$totaltransaction = $this->db->query("SELECT COUNT(*) AS total FROM {$per}withdraw_request {$where}");
		$totaltransaction = $totaltransaction->row['total'];
		$results = $this->db->query("SELECT * FROM {$per}withdraw_request {$where} ORDER BY date_added DESC LIMIT ".$filter['start'].",".$filter['limit']." ");
		$results = $results->rows;
		$data['requests'] = array();
		$this->load->model('customer/customer');
		foreach ($results as $result) {
			$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
			$data['requests'][] = array(
				'request_id'  => $result['request_id'],
				'customer_id' => $result['customer_id'],
				'description' => $result['description'],
				'status' => $result['status'],
				'customer' => $customer_info['email'].' - '.$customer_info['telephone'],
				'price' => $this->currency->format($result['amount'],$this->config->get('config_currency')),
				'date' => date('d-m-Y h:i:s A',strtotime($result['date_added'])),
				'c_link'       => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'], 'SSL')
			);
		}
		$this->load->language('e_wallet/e_wallet');
		$data['breadcrumbs'] = array( array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
			), array(
				'text' => $this->language->get('withdraw_heading_title'),
				'href' => $this->url->link('e_wallet/e_wallet/withdraw_request', 'user_token=' . $this->session->data['user_token'], 'SSL')
			)
		);
		$data['delete'] = $this->url->link('e_wallet/e_wallet/deleterequest', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['approveurl'] = $this->url->link('e_wallet/e_wallet/wapproverequest', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['rejecturl'] = $this->url->link('e_wallet/e_wallet/wrejectrequest', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['formurl'] = $this->url->link('e_wallet/e_wallet/withdraw_request', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['dis_edit'] = $this->url->link('e_wallet/e_wallet/wdis_edit', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['heading_title'] = $this->language->get('withdraw_heading_title');
		$data['text_list'] = $this->language->get('withdraw_text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_date'] = $this->language->get('column_date');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$data['sort_name'] = $this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['sort_sort_order'] = $this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$pagination = new Pagination();
		$pagination->total = $totaltransaction;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token']. '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totaltransaction) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totaltransaction - $this->config->get('config_limit_admin'))) ? $totaltransaction : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totaltransaction, ceil($totaltransaction / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('e_wallet/withdraw_request', $data));
	}
	public function wapproverequest() {
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $request_id) {
				$this->wapproverequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/withdraw_request', 'user_token=' . $this->session->data['user_token'].$url, 'SSL'));
		}
		$this->withdraw_request();
	}
	public function wrejectrequest() {
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $request_id) {
				$this->wrejectrequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/withdraw_request', 'user_token=' . $this->session->data['user_token'].$url, 'SSL'));
		}
		$this->withdraw_request();
	}
	public function wapproverequests($id){
		$per = DB_PREFIX;
		$sql = "SELECT * FROM `{$per}withdraw_request` WHERE request_id = '".(int)$id."' AND status = '0'";
		$request = $this->db->query($sql)->row;
		if($request){
			$ststus = 'Approved';
			$adddata = array(
				'customer_id' => $request['customer_id'],
				'amount' => $request['amount'],
				'description' => 'Request Approved : '.$request['description'].'.'
			);
			$desc="Request Approved : ".$request['description'];
			//$transaction_id = $this->addtransaction($adddata);
			$str = "UPDATE `" . DB_PREFIX . "withdraw_request` SET description='".$this->db->escape($desc)."', status = '1' WHERE request_id = '".(int)$id."'";
			 
			$this->db->query($str);
			$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  customer_id = {$request['customer_id']}")->row;
			if($c_info){
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$subject = $this->config->get('e_wallet_title')." Request ".$ststus.".";
				$message = 'Your Withdraw Request '.$ststus." From Admin \n".
					" Description : ".$request['description']." \n ".
					" Amount : ".$request['amount']." \n ".
					" Date : ".date('d-m-Y h:i:s A')." \n ";
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				$mail->setTo($this->config->get('config_email'));
				$mail->send();
				$mail->setTo($c_info['email']);
				$mail->send();
			}
			return true;
		}
		return false;
	}
	public function wrejectrequests($id){
		$per = DB_PREFIX;
		$sql = "SELECT * FROM `{$per}withdraw_request` WHERE request_id = '".(int)$id."' AND status = '0'";
		$request = $this->db->query($sql)->row;
		if($request){
			$adddata = array(
				'customer_id' => $request['customer_id'],
				'amount' => $request['amount'],
				'description' => 'Request Rejected : '.$request['description'].'.'
			);
			$transaction_id =$this->addtransaction($adddata);
			$desc="Request Rejected : ".$request['description'];			
			$str = "UPDATE `" . DB_PREFIX . "withdraw_request` SET description='".$this->db->escape($desc)."', status = '2',transaction_id = '{$transaction_id}' WHERE request_id = '".(int)$id."'";
			$this->db->query($str);
			$ststus = 'Rejected';
			$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  customer_id = {$request['customer_id']}")->row;
			if($c_info){
				$subject = $this->config->get('e_wallet_title')." Request ".$ststus.".";
				$message = 'Your Withdraw Request '.$ststus." From Admin \n".
					" Description : ".$request['description']." \n ".
					" Amount : ".$request['amount']." \n ".
					" Date : ".date('d-m-Y h:i:s A')." \n ";
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				$mail->setTo($c_info['email']);
				$mail->send();
			}
			return true;
		}
		return false;
	}
	public function wdeleterequest() {
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $request_id) {
				$this->wdeleterequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/withdraw_request', 'user_token=' . $this->session->data['user_token'].$url, 'SSL'));
		}
		$this->withdraw_request();
	}
	public function wdeleterequests($id){
		$str = "DELETE FROM `" . DB_PREFIX . "withdraw_request` WHERE request_id = '".(int)$id."'";
		return $this->db->query($str);
	}
}