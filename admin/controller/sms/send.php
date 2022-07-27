<?php
class ControllerSmsSend extends Controller {
	private $error = array();

	public function index() {
		$data = $this->load->language('sms/send');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['user_token'] = $this->session->data['user_token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sms/send', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sms/send', $data));
	}

	public function sms() {
		$this->load->language('sms/send');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'sms/send')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

			if (!$this->request->post['message']) {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if ($this->request->post['to'] == 'custom' && !$this->request->post['customs']) {
				$json['error']['custom'] = $this->language->get('error_custom');
			}

			if (!$json) {
				$this->load->model('customer/customer');

				$this->load->model('customer/customer_group');

				$this->load->model('sale/order');

				$this->load->model('sms/opc_sms');

				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}

				$telephone_total = 0;

				$telephones = array();

				switch ($this->request->post['to']) {
					case 'newsletter':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);

						$telephone_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$telephones[] = $result['telephone'];
						}
						break;
					case 'customer_all':
						$customer_data = array(
							'start' => ($page - 1) * 10,
							'limit' => 10
						);

						$telephone_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$telephones[] = $result['telephone'];
						}
						break;
					case 'customer_group':
						$customer_data = array(
							'filter_customer_group_id' => $this->request->post['customer_group_id'],
							'start'                    => ($page - 1) * 10,
							'limit'                    => 10
						);

						$telephone_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$telephones[$result['customer_id']] = $result['telephone'];
						}
						break;
					case 'customer':
						if (!empty($this->request->post['customer'])) {
							foreach ($this->request->post['customer'] as $customer_id) {
								$customer_info = $this->model_customer_customer->getCustomer($customer_id);

								if ($customer_info) {
									$telephones[] = $customer_info['telephone'];
								}
							}
						}
						break;
					case 'affiliate_all':
						$affiliate_data = array(
							'start' => ($page - 1) * 10,
							'limit' => 10
						);

						$telephone_total = $this->model_customer_customer->getTotalAffiliates($affiliate_data);

						$results = $this->model_customer_customer->getAffiliates($affiliate_data);

						foreach ($results as $result) {
							$telephones[] = $result['telephone'];
						}
						break;
					case 'affiliate':
						if (!empty($this->request->post['affiliate'])) {
							foreach ($this->request->post['affiliate'] as $affiliate_id) {
								$affiliate_info = $this->model_customer_customer->getAffiliate($affiliate_id);

								if ($affiliate_info) {
									$telephones[] = $affiliate_info['telephone'];
								}
							}
						}
						break;
					case 'product':
						if (isset($this->request->post['product'])) {
							$telephone_total = $this->model_sms_opc_sms->getTotalTelephonesByProductsOrdered($this->request->post['product']);

							$results = $this->model_sms_opc_sms->getTelephonesByProductsOrdered($this->request->post['product'], ($page - 1) * 10, 10);

							foreach ($results as $result) {
								$telephones[] = $result['telephone'];
							}
						}
						break;
					case 'custom':
						$telephones = explode(',', $this->request->post['customs']);
						break;
				}

				if ($telephones) {
					$json['success'] = $this->language->get('text_success');

					$start = ($page - 1) * 10;
					$end = $start + 10;

					if ($end < $telephone_total) {
						$json['success'] = sprintf($this->language->get('text_sent'), $start, $telephone_total);
					}

					if ($end < $telephone_total) {
						$json['next'] = str_replace('&amp;', '&', $this->url->link('sms/send/sms', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true));
					} else {
						$json['next'] = '';
					}

					$message = html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8');

					$this->registry->set('sms', new Sms($this->registry));

					$this->sms->sendSMS($telephones, $message);
				} else {
					$json['error']['telephone'] = $this->language->get('error_telephone');
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customer($route = '', $request = '', $response = '') {

		$this->load->language('sms/send');

	  $json = array();

		$customer_id = 0;

		if ($response) {
			$customer_id = $response;
		} elseif (isset($request[0]) && $request[0]) {
			$customer_id = $request[0];
		}

		$this->registry->set('sms', new Sms($this->registry));

	  if ($this->config->get('module_opc_sms_status') && $this->config->get('module_opc_sms_customer_add')) {
      $this->sms->sendToCustomer($customer_id);
	  }

	  $json['success'] = $this->language->get('text_success');

	  $this->response->addHeader('Content-Type: application/json');
	  $this->response->setOutput(json_encode($json));
	}
}
