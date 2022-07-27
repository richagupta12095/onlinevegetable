<?php
class ControllerExtensionModuleewallet extends Controller {
	private $error = array();
	public function index() {		 
		$data = array_merge($this->load->language('e_wallet/e_wallet'),array());
		$this->load->language('e_wallet/e_wallet');

		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('e_wallet', $this->request->post);
			$this->session->data['success'] = $data['text_success'];
			$this->response->redirect($this->url->link('extension/module/e_wallet', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		$data['error_warning'] = '';
		if (isset($this->error['warning'])) $data['error_warning'] = $this->error['warning'];
		$data['breadcrumbs'] = array(
			array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
			),array(
				'text' => $this->language->get('text_module'),
				'href' => $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], 'SSL')
			),array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/e_wallet', 'user_token=' . $this->session->data['user_token'], 'SSL')
			)
		);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['action'] = $this->url->link('extension/module/e_wallet', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$payment = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'payment'");
		$data['payments'] = array();
		if($payment->num_rows){
			foreach ($payment->rows as $p) {
				$data['payments'][] = $p['code'];
			}
		}
		 
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$option = array(
			'e_wallet_title',
			'e_wallet_status',
			'e_wallet_max_add',
			'e_wallet_min_add',
			'e_wallet_image',
			'e_wallet_icon',
			'e_wallet_payments',
			'e_wallet_max_send',
			'e_wallet_min_send',
			'e_wallet_min_withdraw',
			'e_wallet_max_withdraw',
			'e_wallet_refund_order_id',
			'e_wallet_processing_status',
			'e_wallet_complete_status',
		);
		foreach ($option as $key) {
			if (isset($this->request->post[$key])) $data[$key] = $this->request->post[$key];
			else $data[$key] = $this->config->get($key);
		}
		$this->load->model('tool/image');
		if($data['e_wallet_image']) $data['image_thumb'] = $this->model_tool_image->resize($data['e_wallet_image'], 100, 100);
		else $data['image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if($data['e_wallet_icon']) $data['icon_thumb'] = $this->model_tool_image->resize($data['e_wallet_icon'], 100, 100);
		else $data['icon_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/module/e_wallet', $data));
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/e_wallet')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	public function install(){
		$per = DB_PREFIX;
		$this->db->query("CREATE TABLE IF NOT EXISTS `{$per}e_wallet_transaction`(
		  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(11) NOT NULL,
		  `price` double NOT NULL,
		  `description` text NOT NULL,
		  `date_added` datetime NOT NULL,
		  `balance` VARCHAR( 50 ) NOT NULL,
		  PRIMARY KEY (`transaction_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
		$this->db->query("CREATE TABLE IF NOT EXISTS `{$per}cod_request`(
		  `request_id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(11) NOT NULL,
		  `amount` double NOT NULL,
		  `description` text NOT NULL,
		  `date_added` datetime NOT NULL,
		  `status` SET(  '0',  '1',  '2' ) NOT NULL DEFAULT  '0',
		  `transaction_id` int(11) NOT NULL,
		  PRIMARY KEY (`request_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
		$this->db->query("CREATE TABLE IF NOT EXISTS `{$per}withdraw_request`(
		  `request_id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(11) NOT NULL,
		  `amount` double NOT NULL,
		  `description` text NOT NULL,
		  `date_added` datetime NOT NULL,
		  `status` SET(  '0',  '1',  '2' ) NOT NULL DEFAULT  '0',
		  `transaction_id` int(11) NOT NULL,
		  PRIMARY KEY (`request_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
		$this->db->query("CREATE TABLE IF NOT EXISTS `{$per}e_wallet_bank` (
		  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(11) NOT NULL,
		  `bank_name` varchar(50) NOT NULL,
		  `branch_code` varchar(50) NOT NULL,
		  `swift` varchar(50) NOT NULL,
		  `ifsc` varchar(50) NOT NULL,
		  `ac_name` varchar(50) NOT NULL,
		  `ac_no` varchar(50) NOT NULL,
		  PRIMARY KEY (`bank_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/e_wallet');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/payment/e_wallet_payment');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/total/e_wallet_total');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'e_wallet/e_wallet');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/e_wallet');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/payment/e_wallet_payment');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/total/e_wallet_total');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'e_wallet/e_wallet');
	
		$this->load->model('setting/extension');
		$this->model_setting_extension->install('payment', 'e_wallet_payment');
		$this->load->controller('extension/payment/e_wallet_payment/install');
		$this->model_setting_extension->install('total', 'e_wallet_total');
		$this->load->controller('extension/total/e_wallet_total/install');

		$check_payment=$this->db->query("SELECT code FROM ".DB_PREFIX."setting WHERE code='payment_e_wallet'");
		if(!$check_payment->num_rows){
			$this->load->model('setting/setting');
			
			$array=array(
				'payment_e_wallet_payment_status' => 1,
				'payment_e_wallet_payment_geo_zone_id' => 0,
				'payment_e_wallet_payment_order_status_id' => $this->config->get('config_order_status_id'),
				'payment_e_wallet_payment_total' => 1,
				'payment_e_wallet_payment_sort_order' => 0,
			);
			$this->model_setting_setting->editSetting('payment_e_wallet_payment', $array);
			$array=array(
				'total_total_e_wallet_total_title' => 'e_wallet',
				'total_total_e_wallet_total_status' => 1,
				'total_total_e_wallet_total_sort_order' => ((int)$this->config->get('total_sort_order')-1),
			);
			$this->model_setting_setting->editSetting('total_e_wallet_total_total', $array);
			
		}

	}
	public function uninstall(){
		$per = DB_PREFIX;
		$this->db->query("DROP TABLE `{$per}e_wallet_transaction`");
		$this->db->query("DROP TABLE `{$per}cod_request`");
		$this->db->query("DROP TABLE `{$per}e_wallet_bank`");
		$this->db->query("DROP TABLE `{$per}withdraw_request`");
		$this->load->model('setting/extension');
		$this->model_setting_extension->uninstall('payment', "e_wallet_payment");
		$this->load->controller('extension/payment/e_wallet_payment/uninstall');
		$this->model_setting_extension->uninstall('total', "e_wallet_total");
		$this->load->controller('extension/total/e_wallet_total/uninstall');

	}
}