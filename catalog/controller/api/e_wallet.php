<?php
class ControllerApiEwallet extends Controller {
	private $error = array();
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/e_wallet', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/e_wallet');
		$heading_title = sprintf($this->language->get('heading_title'), $this->config->get('e_wallet_title'));
		$this->document->setTitle($heading_title);
		$data['heading_title'] = $heading_title;
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');

		if ($this->config->get('theme_default_directory') != 'default') {
			$this->document->addStyle('catalog/view/javascript/e_wallet.css');
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'] = array(array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
		), array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL'),
		), array(
			'text' => $this->config->get('e_wallet_title'),
			'href' => $this->url->link('account/e_wallet', '', 'SSL'),
		));
		$data['success'] = $data['error'] = '';
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		if (isset($this->session->data['error'])) {
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
		$data['text_add_money'] = sprintf($this->language->get('text_add_money'), $this->config->get('e_wallet_title'));
		$data['add_money'] = $this->url->link('account/e_wallet/add_money');
		$data['formurl'] = $this->url->link('account/e_wallet');
		$this->load->model('account/e_wallet');
		$page = 1;
		$limit = 20;
		$url = '';
		$data['ccurrency'] = $this->currency;
		if (isset($this->request->get['page'])) {
			$page = (int) $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int) $this->request->get['limit'];
		}

		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
		);
		$filter['datefrom'] = $data['datefrom'] = date('m/d/Y');
		$filter['dateto'] = $data['dateto'] = date('m/d/Y');
		if (isset($this->request->request['datefrom'])) {
			$filter['datefrom'] = $this->request->request['datefrom'];
			$url .= '&datefrom=' . $this->request->request['datefrom'];
			$data['datefrom'] = date('m/d/Y', strtotime($this->request->request['datefrom']));
		}
		if (isset($this->request->request['dateto'])) {
			$filter['dateto'] = $this->request->request['dateto'];
			$url .= '&dateto=' . $this->request->request['dateto'];
			$data['dateto'] = date('m/d/Y', strtotime($this->request->request['dateto']));
		}
		$this->load->model('tool/image');
		$data['e_wallet_icon_url'] = $this->model_tool_image->resize($this->config->get('e_wallet_icon'), 30, 30);
		if (isset($this->session->data['currency'])) {
			$config_currency = $this->session->data['currency'];
			$data['config_currency'] = $this->session->data['currency'];
		} else {
			$config_currency = $this->config->get('config_currency');
			$data['config_currency'] = $this->config->get('config_currency');
		}
		$bank = $this->model_account_e_wallet->getbank();
		if ($bank) {
			$data['bank'] = array(
				'name' => $bank['bank_name'],
				'branch_number' => $bank['branch_code'],
				'swift_code' => $bank['swift'],
				'ifsc_code' => $bank['ifsc'],
				'account_name' => $bank['ac_name'],
				'account_number' => $bank['ac_no'],
			);
		} else {
			$data['bank'] = array('name' => '', 'branch_number' => '', 'swift_code' => '', 'ifsc_code' => '', 'account_name' => '', 'account_number' => '');
		}
		$data['balance'] = $this->currency->format($this->model_account_e_wallet->getBalance(), $config_currency);

		$e_wallet_list = $this->model_account_e_wallet->gettransaction($filter);
		$totaltrasaction = $this->model_account_e_wallet->gettransactiontotal($filter);
		$data['openningbalance'] = $this->model_account_e_wallet->getopenningbalance($filter);
		$data['e_wallet_list'] = array();
		foreach ($e_wallet_list as $v) {
			$data['e_wallet_list'][] = array(
				'transaction_id' => $v['transaction_id'],
				'description' => $v['description'],
				'credit' => ($v['price'] >= 0 ? $this->currency->format($v['price'], $config_currency) : ''),
				'debit' => ($v['price'] < 0 ? $this->currency->format(abs($v['price']), $config_currency) : ''),
				'balance' => $this->currency->format($v['balance'], $config_currency),
				'o_credit' => ($v['price'] > 0 ? $v['price'] : 0),
				'o_debit' => ($v['price'] < 0 ? abs($v['price']) : 0),
				'o_balance' => $v['balance'],
				'date' => date('d-m-Y h:i A', strtotime($v['date_added'])),
			);
		}
		$pagination = new Pagination();
		$pagination->total = $totaltrasaction;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('account/e_wallet', '&page={page}' . $url);
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

	public function getCustomerBalance() {

		$data = array();
		$json = array();
		$input = $this->request->post;
		$this->load->model('api/e_wallet');
		if (isset($input['customer_id'])) {
			$this->load->model('account/e_wallet');
			$data['balance'] = $this->model_account_e_wallet->getBalance($input);
			
			$page = 1;
			$limit = 20;
			$url = '';

			$filter = array(
				'start' => ($page - 1) * $limit,
				'limit' => $limit,
			);
			$filter['customer_id'] = $input['customer_id'];

			if (isset($input['datefrom'])) {
				$filter['datefrom'] = $input['datefrom'];
			} else {
				//$filter['datefrom'] = $data['datefrom'] = date('m/d/Y');
			}
			if (isset($input['dateto'])) {
				$filter['dateto'] = $input['dateto'];
			} else {
				//$filter['dateto'] = $data['dateto'] = date('m/d/Y');
			}

			$bank = $this->model_account_e_wallet->getbank($input);
			if ($bank) {
				$data['bank'] = array(
					'name' => $bank['bank_name'],
					'branch_number' => $bank['branch_code'],
					'swift_code' => $bank['swift'],
					'ifsc_code' => $bank['ifsc'],
					'account_name' => $bank['ac_name'],
					'account_number' => $bank['ac_no'],
				);
			} else {
				$data['bank'] = array('name' => '', 'branch_number' => '', 'swift_code' => '', 'ifsc_code' => '', 'account_name' => '', 'account_number' => '');
			}

			$e_wallet_list = $this->model_account_e_wallet->gettransaction($filter);
			$totaltrasaction = $this->model_account_e_wallet->gettransactiontotal($filter);
			$data['openningbalance'] = $this->model_account_e_wallet->getopenningbalance($filter);
			$data['e_wallet_list'] = array();
			foreach ($e_wallet_list as $v) {
				$data['e_wallet_list'][] = array(
					'transaction_id' => $v['transaction_id'],
					'description' => $v['description'],
					'credit' => ($v['price'] >= 0 ? $v['price'] : ''),
					'debit' => ($v['price'] < 0 ? abs($v['price']) : ''),
					'balance' => $v['balance'],
					'o_credit' => ($v['price'] > 0 ? $v['price'] : 0),
					'o_debit' => ($v['price'] < 0 ? abs($v['price']) : 0),
					'o_balance' => $v['balance'],
					'date' => date('d-m-Y h:i A', strtotime($v['date_added'])),
				);
			}
			$json['status'] = 'Success';
			$json['data'][] = $data;
		} else {
			$json['status'] = 'error';
			$json['data'][] = 'Please put the customer id.';
		}
		$resbtalance = $this->model_api_e_wallet->getBalance($this->request->post);
		if(empty($resbtalance)){
			$json['balance'] = '';
		}else{
			$json['balance'] = $resbtalance;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function send_money() {
		$input = $this->request->post;
		if (isset($input['customer_id'])) {

			$this->load->model('api/e_wallet');

			$bal_value_used = $this->model_api_e_wallet->getBalance($input);
			if(empty($bal_value_used)){
				$data['balance'] = '';
			}else{
				$data['balance'] = $bal_value_used;
			}

			$balance = $data['balance'];
			$data['error_warning'] = '';

			if (isset($this->request->post['email'])) {
				$this->load->model('account/customer');
				if (!isset($input['amount']) || (int) $input['amount'] <= 0) {
					$data['error_warning'] = "Invalid Amount..!";
				}
				if (!$data['error_warning']) {

					$amount = $this->request->post['amount'];

					$amountmax = (float) $this->config->get('e_wallet_max_send');

					$amountmin = $this->config->get('e_wallet_min_send');

					if ((int) $amount > $this->config->get('e_wallet_max_send')) {

						$data['error_warning'] = 'Please Enter Below ' . $amountmax . ' Amount..!';
					} else if ((int) $amount < $this->config->get('e_wallet_min_send')) {

						$data['error_warning'] = 'Please Enter Above ' . $amountmin . ' Amount..!';
					} else if ((float) $balance < (float) $amount) {

						$data['error_warning'] = "Insufficient Balance .!";
					} else {

						$email = $this->request->post['email'];

						$per = DB_PREFIX;
						$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  (`email` LIKE '{$email}'  or `telephone` = '{$email}') ");

						$sender_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  customer_id= '{$input['customer_id']}'");
						if ($c_info->num_rows != 1 && $c_info->row['customer_id'] == $input['customer_id']) {
							$data['error_warning'] = 'Invalid Email Id / Telephone Or not Exists..';
						}
						$c_info = $c_info->row;
					}
				}
				if (!$data['error_warning']) {
					$d = array(
						'customer_id' => $c_info['customer_id'],
						'name' => $c_info['firstname'] . ' ' . $c_info['lastname'],
						'amount' => $amount,
						'email' => $c_info['email'],
						'sender_customer_id' => $sender_info->row['customer_id'],
						'sender_name' => $sender_info->row['firstname'] . ' ' . $sender_info->row['lastname'],
						'sender_email' => $sender_info->row['email'],
					);

					$this->model_api_e_wallet->sendmoney($d);
					$data['status'] = 'success';
					$data['message'] = 'Money Sent Successfully';

				} else {
					$data['status'] = 'error';
					$data['message'] = 'error in money sent';
				}
			}
		}
		$resbtalance = $this->model_api_e_wallet->getBalance($this->request->post);
		if(empty($resbtalance)){
			$data['balance'] = '';
		}else{
			$data['balance'] = $resbtalance;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	public function withdrawreq() {

		$this->load->model('api/e_wallet');
		if (isset($this->request->post['amount']) && isset($this->request->post['customer_id'])) {
			$bal_value_used = $this->model_api_e_wallet->getBalance($this->request->post);
			if(empty($bal_value_used)){
				$data['balance'] = '';
			}else{
				$data['balance'] = $bal_value_used;
			}
			$balance = $data['balance'];
			if (!isset($this->request->post['amount']) || (int) $this->request->post['amount'] <= 0) {
				$data['error_warning'] = "Invalid Amount..!";
			}

			if (!isset($data['error_warning'])) {

				$amount = $this->request->post['amount'];
				$amountmax = (float) $this->config->get('e_wallet_max_send');
				$amountmin = (float) $this->config->get('e_wallet_min_send');

				if ((int) $amount > $this->config->get('e_wallet_max_withdraw')) {
					$data['error_warning'] = 'Please Enter Below ' . $amountmax . ' Amount..!';
				} else if ((int) $amount < $this->config->get('e_wallet_min_withdraw')) {
					$data['error_warning'] = 'Please Enter Above ' . $amountmin . ' Amount..!';
				} else if ((float) $balance < (float) $amount) {
					$data['error_warning'] = "Insufficient Balance .!";
				}
			}

			if (!isset($data['error_warning'])) {
				$data['status'] = "success";
				$d = array('amount' => $amount, 'customer_id' => $this->request->post['customer_id']);
				$this->model_api_e_wallet->withdrawmoney($d);
			}
		} else {
			$data['status'] = "failed";
			$data['error_warning'] = "Invalid Customer Id or Amount..!";
		}
		$resbtalance = $this->model_api_e_wallet->getBalance($this->request->post);
		if(empty($resbtalance)){
			$data['balance'] = '';
		}else{
			$data['balance'] = $resbtalance;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	public function add_money() {
		$json = array();
		$this->load->model('api/e_wallet');
		if (!isset($this->request->post['amount']) || (int) $this->request->post['amount'] == 0) {
			$json['status'] = 'error';
			$json['message'] = 'Please Enter Valid Amount..!';
		} else {

			$amount = $this->request->post['amount'];
			$amountmax = (float) $this->config->get('e_wallet_max_add');
			$amountmin = (float) $this->config->get('e_wallet_min_add');

			if ((int) $amount > $this->config->get('e_wallet_max_add')) {
				$json['message'] = 'Please Enter Below ' . $amountmax . ' Amount..!';
			} else if ((int) $amount < $this->config->get('e_wallet_min_add')) {
				$json['message'] = 'Please Enter Above ' . $amountmin . ' Amount..!';
			} else {
				
				$data = array(
					'desc' => 'Add Money In Wallet with reference transaction id ' . $this->request->post['transaction_id'],
					'customer_id' => $this->request->post['customer_id'],
					'customer_name' => $this->request->post['customer_name'],
					'customer_email' => $this->request->post['customer_email'],
					'message' => 'Add Money In Wallet..!',
					'amount' => $amount,
				);
				$json['message'] = 'Add Money In Wallet..!';
				$json['status'] = 'success';
				$this->model_api_e_wallet->addtransaction($data);
			}

		}
		$resbtalance = $this->model_api_e_wallet->getBalance($this->request->post);
		if(empty($resbtalance)){
			$data['balance'] = '';
		}else{
			$data['balance'] = $resbtalance;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function add_bank() {
		$json = array();
		$input = $this->request->post;
		if (isset($input['customer_id'])) {
			$this->load->model('api/e_wallet');
			$data = array(
				'bank_name' => $input['bank_name'],
				'branch_code' => $input['branch_code'],
				'swift' => $input['swift'],
				'ifsc' => $input['ifsc'],
				'ac_name' => $input['ac_name'],
				'ac_no' => $input['ac_no'],
			);
			$data['customer_id'] = $input['customer_id'];
			$this->model_api_e_wallet->setbank($data);
			$json['status'] = 'success';
			$json['message'] = 'Bank Added Successfully';
		} else {
			$json['status'] = 'error';
			$json['message'] = 'Customer Id not found';
		}
		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($json);
	}
}