<?php
class Controlleraccountewallet extends Controller {
	private $error = array();
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/e_wallet', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/e_wallet');
		$heading_title = sprintf($this->language->get('heading_title'),$this->config->get('e_wallet_title'));
		$this->document->setTitle($heading_title);
		$data['heading_title'] = $heading_title;
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');		
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		 
		if($this->config->get('theme_default_directory') != 'default'){
			$this->document->addStyle('catalog/view/javascript/e_wallet.css');	
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'] = array(array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		),array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		),array(
			'text' => $this->config->get('e_wallet_title'),
			'href' => $this->url->link('account/e_wallet', '', 'SSL')
		));
		$data['success'] = $data['error'] = '';
		if(isset($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		if(isset($this->session->data['error'])){
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}
		$data['add_money_form'] = $this->url->link('account/e_wallet/get_form');
		$data['text_balance'] = $this->language->get('text_balance');
		$data['text_amount'] = $this->language->get('text_amount');
		$data['text_transaction_id'] = $this->language->get('text_transaction_id');
		$data['text_desc'] = $this->language->get('text_desc');
		$data['text_date'] = $this->language->get('text_date');
		$data['text_credit'] = $this->language->get('text_credit');
		$data['text_debit'] = $this->language->get('text_debit');
		$data['column_balance'] = $this->language->get('column_balance');
		$data['text_send_money'] = $this->language->get('text_send_money');
		$data['text_withdrawreq'] = $this->language->get('text_withdrawreq');
		$data['text_add_bank'] = $this->language->get('text_add_bank');
		$data['send_money'] = $this->url->link('account/e_wallet/send_money');
		$data['withdrawreq'] = $this->url->link('account/e_wallet/withdrawreq');
		$data['add_bank'] = $this->url->link('account/e_wallet/add_bank');
		$data['text_add_money'] = sprintf($this->language->get('text_add_money'),$this->config->get('e_wallet_title'));
		$data['add_money'] = $this->url->link('account/e_wallet/add_money');
		$data['formurl'] = $this->url->link('account/e_wallet');
		$this->load->model('account/e_wallet');
		$page = 1;
		$limit = 20;
		$url ='';
		$data['ccurrency'] = $this->currency;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		$filter['datefrom'] = $data['datefrom'] = date('m/d/Y');
		$filter['dateto'] = $data['dateto'] = date('m/d/Y');
		if(isset($this->request->request['datefrom'])){
			$filter['datefrom'] = $this->request->request['datefrom'];
			$url .='&datefrom='.$this->request->request['datefrom'];
			$data['datefrom'] = date('m/d/Y',strtotime($this->request->request['datefrom']));
		}
		if(isset($this->request->request['dateto'])){
			$filter['dateto'] = $this->request->request['dateto'];
			$url .= '&dateto='.$this->request->request['dateto'];
			$data['dateto'] = date('m/d/Y',strtotime($this->request->request['dateto']));
		}
		$this->load->model('tool/image');
		$data['e_wallet_icon_url'] = $this->model_tool_image->resize($this->config->get('e_wallet_icon'), 30,30);
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];
			$data['config_currency'] =$this->session->data['currency'];
		}else{
			$config_currency =$this->config->get('config_currency');
			$data['config_currency'] =$this->config->get('config_currency');
		}
		$bank = $this->model_account_e_wallet->getbank();
		if($bank){
			$data['bank'] = array(
				'name' => $bank['bank_name'],
				'branch_number' => $bank['branch_code'],
				'swift_code' => $bank['swift'],
				'ifsc_code' => $bank['ifsc'],
				'account_name' => $bank['ac_name'],
				'account_number' => $bank['ac_no'],
			);
		}else{
			$data['bank'] = array('name'=>'','branch_number'=>'','swift_code'=>'','ifsc_code'=>'','account_name'=>'','account_number'=>'');
		}
		$data['balance'] = $this->currency->format($this->model_account_e_wallet->getBalance(),$config_currency);
 
		$e_wallet_list = $this->model_account_e_wallet->gettransaction($filter);
		$totaltrasaction = $this->model_account_e_wallet->gettransactiontotal($filter);
		$data['openningbalance'] = $this->model_account_e_wallet->getopenningbalance($filter);
		$data['e_wallet_list'] = array();
		foreach ($e_wallet_list as $v){
			$data['e_wallet_list'][] = array(
				'transaction_id' => $v['transaction_id'],
				'description' => $v['description'],
				'credit' => ($v['price'] >= 0 ? $this->currency->format($v['price'],$config_currency) : ''),
				'debit' => ($v['price'] < 0 ? $this->currency->format(abs($v['price']),$config_currency) : ''),
				'balance' => $this->currency->format($v['balance'],$config_currency),
				'o_credit' => ($v['price'] > 0 ? $v['price'] : 0),
				'o_debit' => ($v['price'] < 0 ? abs($v['price']) :0),
				'o_balance' => $v['balance'],
				'date' => date('d-m-Y h:i A',strtotime($v['date_added']))
			); 
		}
		$pagination = new Pagination();
		$pagination->total = $totaltrasaction;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('account/e_wallet','&page={page}'.$url);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totaltrasaction) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($totaltrasaction - $limit)) ? $totaltrasaction : ((($page - 1) * $limit) + $limit), $totaltrasaction, ceil($totaltrasaction / $limit));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('account/e_wallet', $data));
	}
	public function send_money(){
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/send_money', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		$this->document->addStyle('catalog/view/javascript/e_wallet.css');	

		$data = $this->load->language('account/e_wallet');
		$this->load->model('account/e_wallet');
		$balance = $this->model_account_e_wallet->getBalance();
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];				
		}else{
			$config_currency =$this->config->get('config_currency');				
		}
		$data['balance'] = $this->currency->format($balance,$config_currency);
		$data['error_warning'] = '';
		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['email'])){
			$this->load->model('account/customer');
			if(!isset($this->request->post['amount']) || (int)$this->request->post['amount'] <= 0){
				$data['error_warning'] = "Invalid Amount..!";
			}
			if(!$data['error_warning']){
				$s_currency = $this->session->data['currency'];
				$c_currency = $this->config->get('config_currency');
				$amount = $this->currency->convert($this->request->post['amount'], $s_currency, $c_currency);
				$amountmax = $this->currency->format((float)$this->config->get('e_wallet_max_send'),$s_currency);
				$amountmin = $this->currency->format((float)$this->config->get('e_wallet_min_send'),$s_currency);
				if((int)$amount > $this->config->get('e_wallet_max_send')){
					$data['error_warning'] = 'Please Enter Below '.$amountmax.' Amount..!';
				}else if((int)$amount < $this->config->get('e_wallet_min_send')){
					$data['error_warning'] = 'Please Enter Above '.$amountmin.' Amount..!';
				}else if((float)$balance < (float)$amount){
					$data['error_warning'] = "Insufficient Balance .!";
				}else{
					$email = $this->request->post['email'];
					$per = DB_PREFIX;
					$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  (`email` LIKE '{$email}' or `telephone` = '{$email}')
						AND (`email` NOT LIKE '{$this->customer->getEmail()}' AND `telephone` != '{$this->customer->getTelephone()}')");
					if($c_info->num_rows != 1){
						$data['error_warning'] = 'Invalid Email Id / Telephone Or not Exists..';
					}
					$c_info = $c_info->row;
				}
			}
			if(!$data['error_warning']){
				$d = array(
					'customer_id' => $c_info['customer_id'],
					'name' => $c_info['firstname'].' '.$c_info['lastname'],
					'amount' => $amount,
					'email' => $c_info['email'],
				);
				$this->model_account_e_wallet->sendmoney($d);
				$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
				die;
			}
		}
		$data['send_money'] = $this->url->link('account/e_wallet/send_money');
		$heading_title = sprintf($this->language->get('text_send_money'),$this->config->get('e_wallet_title'));
		$this->document->setTitle($heading_title);
		$data['heading_title'] = $heading_title;
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'] = array(array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		),array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		),array(
			'text' => $this->config->get('e_wallet_title'),
			'href' => $this->url->link('account/e_wallet', '', 'SSL')
		),array(
			'text' => $this->language->get('text_send_money'),
			'href' => $this->url->link('account/e_wallet/send_money', '', 'SSL')
		));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('account/send_money', $data));
	}
	public function withdrawreq(){
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/withdrawreq', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		$this->document->addStyle('catalog/view/javascript/e_wallet.css');	
		
		$data = $this->load->language('account/e_wallet');
		$this->load->model('account/e_wallet');
		$balance = $this->model_account_e_wallet->getBalance();
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];				
		}else{
			$config_currency =$this->config->get('config_currency');				
		}
		$data['balance'] = $this->currency->format($balance,$config_currency);
		$data['error_warning'] = '';
		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['amount'])){
			$this->load->model('account/customer');
			if(!isset($this->request->post['amount']) || (int)$this->request->post['amount'] <= 0){
				$data['error_warning'] = "Invalid Amount..!";
			}
			if(!$data['error_warning']){
				$s_currency = $this->session->data['currency'];
				$c_currency = $this->config->get('config_currency');
				$amount = $this->currency->convert($this->request->post['amount'], $s_currency, $c_currency);
				$amountmax = $this->currency->format((float)$this->config->get('e_wallet_max_send'),$s_currency);
				$amountmin = $this->currency->format((float)$this->config->get('e_wallet_min_send'),$s_currency);
				if((int)$amount > $this->config->get('e_wallet_max_withdraw')){
					$data['error_warning'] = 'Please Enter Below '.$amountmax.' Amount..!';
				}else if((int)$amount < $this->config->get('e_wallet_min_withdraw')){
					$data['error_warning'] = 'Please Enter Above '.$amountmin.' Amount..!';
				}else if((float)$balance < (float)$amount){
					$data['error_warning'] = "Insufficient Balance .!";
				}
			}
			 
			if(!$data['error_warning']){
				$d =array(					
					'amount' => $amount,				
				);
				$this->model_account_e_wallet->withdrawmoney($d);
				$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
				die;
			}
		}
		$data['withdrawreq'] = $this->url->link('account/e_wallet/withdrawreq');
		$heading_title = sprintf($this->language->get('text_withdrawreq'),$this->config->get('e_wallet_title'));
		$this->document->setTitle($heading_title);
		$data['heading_title'] = $heading_title;
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'] = array(array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		),array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		),array(
			'text' => $this->config->get('e_wallet_title'),
			'href' => $this->url->link('account/e_wallet', '', 'SSL')
		),array(
			'text' => $this->language->get('text_withdrawreq'),
			'href' => $this->url->link('account/e_wallet/withdrawreq', '', 'SSL')
		));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('account/withdrawreq', $data));
	}
	public function add_money(){
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/add_money', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		if(!isset($this->request->post['amount']) || (int)$this->request->post['amount'] == 0){
			$this->session->data['error'] = 'Please Enter Valid Amount..!';
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}
		$s_currency = $this->session->data['currency'];
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];				
		}else{
			$config_currency =$this->config->get('config_currency');				
		}
		$amount = $this->currency->convert($this->request->post['amount'], $s_currency, $config_currency);
		$amountmax = $this->currency->format((float)$this->config->get('e_wallet_max_add'),$s_currency);
		$amountmin = $this->currency->format((float)$this->config->get('e_wallet_min_add'),$s_currency);
		if((int)$amount > $this->config->get('e_wallet_max_add')){
			$this->session->data['error'] = 'Please Enter Below '.$amountmax.' Amount..!';
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}else if((int)$amount < $this->config->get('e_wallet_min_add')){
			$this->session->data['error'] = 'Please Enter Above '.$amountmin.' Amount..!';
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}
		$this->load->model('tool/image');
		$this->cart->clear();
		$this->session->data['vouchers'] = array();
		$vouchers_key = 'e_wallet_vouchers';
		$this->session->data['vouchers_key'] = 'e_wallet_vouchers';
		$vimage = 'no_image.png';
		if($this->config->get('e_wallet_image')){
			$vimage = $this->config->get('e_wallet_image');
		}
		$vimage = $this->model_tool_image->resize($vimage, $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
		$this->session->data['vouchers'][$vouchers_key] = array(
			'description'      => 'Add Money In Wallet',
			'to_name'          => $vouchers_key,
			'to_email'         => $this->customer->getEmail(),
			'from_name'        => $this->customer->getFirstName(),
			'from_email'       => $this->customer->getEmail(),
			'voucher_theme_id' => -1,
			'message'          => 'Add Money In Wallet..!',
			'image'            => $vimage,
			'amount'           => $amount,
		);
		$this->response->redirect($this->url->link('checkout/e_checkout', '', 'SSL'));
	}
	public function add_bank(){
		$json = array();
		if(isset($this->request->post['bank'])){
			$this->load->model('account/e_wallet');
			$bank = $this->request->post['bank'];
			$data = array(
				'bank_name' => $bank['name'],
				'branch_code' => $bank['branch_number'],
				'swift' => $bank['swift_code'],
				'ifsc' => $bank['ifsc_code'],
				'ac_name' => $bank['account_name'],
				'ac_no' => $bank['account_number']
			);
			$this->model_account_e_wallet->setbank($data);
			$json['success'] = 1;
		}
		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($json); die;
	}
}