<?php 
class ControllerExtensionPaymentPayU extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('extension/payment/payu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('payment_payu', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if (isset($this->error['salt'])) {
			$data['error_salt'] = $this->error['salt'];
		} else {
			$data['error_salt'] = '';
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
   		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/payu', 'user_token=' . $this->session->data['user_token'], true)
		);
				
		$data['action'] = $this->url->link('extension/payment/payu', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		
		if (isset($this->request->post['payment_payu_title'])) {
			$data['payment_payu_title'] = $this->request->post['payment_payu_title'];
		} elseif($this->config->get('payment_payu_title')) {
			$data['payment_payu_title'] = $this->config->get('payment_payu_title');
		} else {
			$data['payment_payu_title'] = 'PayUMoney';
		}

		if (isset($this->request->post['payment_payu_merchant'])) {
			$data['payment_payu_merchant'] = $this->request->post['payment_payu_merchant'];
		} else {
			$data['payment_payu_merchant'] = $this->config->get('payment_payu_merchant');
		}
		
		if (isset($this->request->post['payment_payu_salt'])) {
			$data['payment_payu_salt'] = $this->request->post['payment_payu_salt'];
		} else {
			$data['payment_payu_salt'] = $this->config->get('payment_payu_salt');
		}

		if (isset($this->request->post['payment_payu_live'])) {
			$data['payment_payu_live'] = $this->request->post['payment_payu_live'];
		} else {
			$data['payment_payu_live'] = $this->config->get('payment_payu_live');
		}
		
		if (isset($this->request->post['payment_payu_total'])) {
			$data['payment_payu_total'] = $this->request->post['payment_payu_total'];
		} else {
			$data['payment_payu_total'] = $this->config->get('payment_payu_total'); 
		} 
		
		if (isset($this->request->post['payment_payu_currency'])) {
			$data['payment_payu_currency'] = $this->request->post['payment_payu_currency'];
		} elseif($this->config->get('payment_payu_currency')) {
			$data['payment_payu_currency'] = $this->config->get('payment_payu_currency');
		} else {
			$data['payment_payu_currency'] = $this->config->get('config_currency');
		}

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
				
		if (isset($this->request->post['payment_payu_completed_status_id'])) {
			$data['payment_payu_completed_status_id'] = $this->request->post['payment_payu_completed_status_id'];
		} else {
			$data['payment_payu_completed_status_id'] = $this->config->get('payment_payu_completed_status_id'); 
		} 

		if (isset($this->request->post['payment_payu_failed_status_id'])) {
			$data['payment_payu_failed_status_id'] = $this->request->post['payment_payu_failed_status_id'];
		} else {
			$data['payment_payu_failed_status_id'] = $this->config->get('payment_payu_failed_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['payment_payu_geo_zone_id'])) {
			$data['payment_payu_geo_zone_id'] = $this->request->post['payment_payu_geo_zone_id'];
		} else {
			$data['payment_payu_geo_zone_id'] = $this->config->get('payment_payu_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['payment_payu_status'])) {
			$data['payment_payu_status'] = $this->request->post['payment_payu_status'];
		} else {
			$data['payment_payu_status'] = $this->config->get('payment_payu_status');
		}
		
		if (isset($this->request->post['payment_payu_sort_order'])) {
			$data['payment_payu_sort_order'] = $this->request->post['payment_payu_sort_order'];
		} else {
			$data['payment_payu_sort_order'] = $this->config->get('payment_payu_sort_order');
		}

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('extension/payment/payu', $data));
	}

	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'extension/payment/payu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['payment_payu_title']) < 1) || (utf8_strlen($this->request->post['payment_payu_title']) > 255)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if (!$this->request->post['payment_payu_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		
		if (!$this->request->post['payment_payu_salt']) {
			$this->error['salt'] = $this->language->get('error_salt');
		}

		return !$this->error;
	}
}
?>