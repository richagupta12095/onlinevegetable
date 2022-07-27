<?php
class ControllerExtensionModuleCiBlogSubscriber extends Controller {
	private $error = array();

	public function index() {

		$data['store_id'] = $store_id = 0;

		if(isset($this->request->get['store_id'])) {
			$data['store_id'] = $store_id = $this->request->get['store_id'];
		}


		$this->load->language('extension/module/ciblog_subscriber');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/ciblog/ciblog.css');
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(VERSION >= '3.0.0.0') {
				$settings = array(
					'module_ciblog_subscriber_status' => $this->request->post['ciblog_subscriber_status']
				);
				$this->model_setting_setting->editSetting('module_ciblog_subscriber', $settings, $store_id);
			}

			$this->model_setting_setting->editSetting('ciblog_subscriber', $this->request->post, $store_id);

			$this->session->data['success'] = $this->language->get('text_success');

			// $this->response->redirect($this->url->link($this->ciblog->admin_extension_page_path, $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&type=module', true));

			$this->response->redirect($this->url->link('extension/module/ciblog_subscriber', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&store_id='. $store_id, true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link($this->ciblog->admin_extension_page_path, $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/ciblog_subscriber', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
		);


		$data['action'] = $this->url->link('extension/module/ciblog_subscriber', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&store_id='. $store_id, true);

		$data['cancel'] = $this->url->link($this->ciblog->admin_extension_page_path, $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&type=module', true);

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		// get all stores
		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();

		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default'),
			'href' => $this->url->link('extension/module/ciblog_subscriber', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&store_id=0', true)
		);
		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name'],
				'href' => $this->url->link('extension/module/ciblog_subscriber', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&store_id=' . $store['store_id'], true)
			);
		}

		$module_info = $this->model_setting_setting->getSetting('ciblog_subscriber', $store_id);

		if (isset($this->request->post['ciblog_subscriber_status'])) {
			$data['ciblog_subscriber_status'] = $this->request->post['ciblog_subscriber_status'];
		} else if (isset($module_info['ciblog_subscriber_status'])) {
			$data['ciblog_subscriber_status'] = $module_info['ciblog_subscriber_status'];
		}  else {
			$data['ciblog_subscriber_status'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/module/ciblog_subscriber', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ciblog_subscriber')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}