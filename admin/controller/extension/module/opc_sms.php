<?php
class ControllerExtensionModuleOpcSms extends Controller {
	private $error = array();

	public function install() {
		$this->load->model('sms/opc_sms');

		$this->load->model('setting/event');

		$this->model_sms_opc_sms->createTable();

		$this->model_setting_event->addEvent('opc_sms', 'admin/model/customer/customer/addCustomer/after', 'sms/send/customer');

		$this->model_setting_event->addEvent('opc_sms', 'catalog/model/account/customer/addCustomer/after', 'sms/send/customer');

		$this->model_setting_event->addEvent('opc_sms', 'catalog/model/checkout/order/addOrderHistory/after', 'sms/send/order');
	}

	public function uninstall() {
		$this->load->model('sms/opc_sms');

		$this->load->model('setting/event');

		$this->model_sms_opc_sms->dropTable();

		$this->model_setting_event->deleteEventByCode('opc_sms');
	}

	public function menu(){
		$this->load->language('extension/module/opc_sms');

		$menus = array();

		$sms = array();

		if ($this->config->get('module_opc_sms_status')) {
			if ($this->user->hasPermission('access', 'extension/module/opc_sms')) {
				$sms[] = array(
					'name'	   => $this->language->get('text_configuration'),
					'href'     => $this->url->link('extension/module/opc_sms', 'user_token=' . $this->session->data['user_token'], true),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'sms/history')) {
				$sms[] = array(
					'name'	   => $this->language->get('text_history_sms'),
					'href'     => $this->url->link('sms/history', 'user_token=' . $this->session->data['user_token'], true),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'sms/send')) {
				$sms[] = array(
					'name'	   => $this->language->get('text_send_sms'),
					'href'     => $this->url->link('sms/send', 'user_token=' . $this->session->data['user_token'], true),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'sms/template')) {
				$sms[] = array(
					'name'	   => $this->language->get('text_template_sms'),
					'href'     => $this->url->link('sms/template', 'user_token=' . $this->session->data['user_token'], true),
					'children' => array()
				);
			}

			if ($sms) {
				$menus = array(
					'id'       => 'menu-sms',
					'icon'	   => 'fa-envelope',
					'name'	   => $this->language->get('text_sms'),
					'href'     => '',
					'children' => $sms
				);
			}
		}

		return $menus;
	}

	public function index() {
		$data = $this->load->language('extension/module/opc_sms');

		if ($this->request->post) {
			$this->request->post = array_map('trim', $this->request->post);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		$this->load->model('sms/opc_sms');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('module_opc_sms', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$opc_error = array(
			'warning',
			'sender',
			'authkey',
		);

		foreach ($opc_error as $key => $value) {
			if (isset($this->error[$value])) {
				$data['error_'.$value] = $this->error[$value];
			} else {
				$data['error_'.$value] = '';
			}
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/opc_sms', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/opc_sms', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

    $opc_module_config = array(
			'status',
			'sender',
			'authkey',
			'country',
			'order_add',
			'customer_add',
			'customer_contact',
		);

    foreach ($opc_module_config as $key => $value) {
      if (isset($this->request->post['module_opc_sms_'.$value])) {
  			$data['module_opc_sms_'.$value] = $this->request->post['module_opc_sms_'.$value];
  		} else {
  			$data['module_opc_sms_'.$value] = $this->config->get('module_opc_sms_'.$value);
  		}
    }

		$data['templates'] = $this->model_sms_opc_sms->getTemplates(array());

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/opc_sms', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/opc_sms')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['module_opc_sms_status']) {
			$opc_error = array(
				'sender',
				'authkey',
			);

			foreach ($opc_error as $key => $value) {
				if (!isset($this->request->post['module_opc_sms_'.$value]) || !$this->request->post['module_opc_sms_'.$value]) {
					$this->error[$value] = $this->language->get('error_'.$value);
				}
			}
		}

		return !$this->error;
	}
}
