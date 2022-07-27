<?php
class ControllerExtensionpaymentewalletpayment extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/e_wallet_payment');
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_e_wallet_payment', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment/e_wallet_payment', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		$data['error_warning'] = '';
		if (isset($this->error['warning'])) $data['error_warning'] = $this->error['warning'];

		$data['breadcrumbs'] = array(
			array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
			),array(
				'text' => $this->language->get('text_payment'),
				'href' => $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL')
			),array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/payment/e_wallet_payment', 'user_token=' . $this->session->data['user_token'], 'SSL')
			),
		);
		$language = array('heading_title','text_edit','text_enabled','text_disabled','text_all_zones','entry_order_status','entry_total','entry_geo_zone','entry_status','entry_sort_order','help_total','button_save','button_cancel');
		foreach ($language as $key) $data[$key] = $this->language->get($key);
		$this->document->setTitle($this->language->get('heading_title'));

		$data['action'] = $this->url->link('extension/payment/e_wallet_payment', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$this->load->model('localisation/order_status');
		$this->load->model('localisation/geo_zone');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		$option = array(
			'payment_e_wallet_payment_total',
			'payment_e_wallet_payment_order_status_id',
			'payment_e_wallet_payment_geo_zone_id',
			'payment_e_wallet_payment_status',
			'payment_e_wallet_payment_sort_order',
		);
		foreach ($option as $key) {
			if (isset($this->request->post[$key])) $data[$key] = $this->request->post[$key];
			else $data[$key] = $this->config->get($key);
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/e_wallet_payment', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/e_wallet_payment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}