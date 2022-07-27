<?php
/*
Total Order Discount v1.4

The Total Order Discount extension is for CMS Opencart 3.x.
It allows to give a discount based on purchase total price or quantity of products in the cart.

Copyright (c) 2018-2019 Andrii Burkatskyi aka underr

https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=33296
https://underr.space/en/notes/projects/project-001.html
https://github.com/underr-ua/ocmod3-total-order-discount
*/

class ControllerExtensionTotalOrderDiscount extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('setting/setting');
		$this->load->language('extension/total/order_discount');

		$this->document->setTitle($this->language->get('heading_title'));

		if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
			$this->model_setting_setting->editSetting('total_order_discount', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect(
				$this->url->link(
					'extension/total/order_discount',
					'user_token=' . $this->session->data['user_token'] . '&type=total',
					true
				)
			);
		}

		if (isset($this->error['permission'])) {
			$data['error_permission'] = $this->error['permission'];
		} else {
			$data['error_permission'] = '';
		}

		if (isset($this->error['discount_title'])) {
			$data['error_discount_title'] = $this->error['discount_title'];
		} else {
			$data['error_discount_title'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link(
				'common/dashboard',
				'user_token=' . $this->session->data['user_token'],
				true
			),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link(
				'extension/total/order_discount',
				'user_token=' . $this->session->data['user_token'] . '&type=total',
				true
			),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link(
				'extension/total/order_discount',
				'user_token=' . $this->session->data['user_token'],
				true
			),
		);

		$data['action'] = $this->url->link(
			'extension/total/order_discount',
			'user_token=' . $this->session->data['user_token'],
			true
		);

		$data['cancel'] = $this->url->link(
			'extension/total/order_discount',
			'user_token=' . $this->session->data['user_token'] . '&type=total',
			true
		);

		if (isset($this->request->post['total_order_discount_status'])) {
			$data['status'] = $this->request->post['total_order_discount_status'];
		} else {
			$data['status'] = $this->config->get('total_order_discount_status');
		}

		if (isset($this->request->post['total_order_discount_base'])) {
			$data['base'] = $this->request->post['total_order_discount_base'];
		} else {
			$data['base'] = $this->config->get('total_order_discount_base');
		}

		if (isset($this->request->post['total_order_discount_point'])) {
			$data['point'] = abs($this->request->post['total_order_discount_point']);
		} else {
			$data['point'] = abs($this->config->get('total_order_discount_point'));
		}

		if (isset($this->request->post['total_order_discount_type'])) {
			$data['type'] = $this->request->post['total_order_discount_type'];
		} else {
			$data['type'] = $this->config->get('total_order_discount_type');
		}

		if (isset($this->request->post['total_order_discount_value'])) {
			$data['value'] = (int)$this->request->post['total_order_discount_value'];
		} else {
			$data['value'] = (int)$this->config->get('total_order_discount_value');
		}

		// Remove percentage discount value if it is more or less then 100%
		if ('P' == $data['type']) {
			if ($data['value'] <= -100 || $data['value'] >= 100) {
				$data['value'] = 0;
			}
		}

		// Remove fixed discount value if it is more then cart total.
		if ('S' == $data['base'] && 'F' == $data['type']) {
			if ($data['value'] >= $data['point']) {
				$data['value'] = 0;
				$data['point'] = 0;
			}
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		foreach ($data['languages'] as $language) {
			$language_id = $language['language_id'];

			if (isset($this->request->post['total_order_discount_title'][$language_id])) {
				$data['discount_title'][$language_id] =
					$this->request->post['total_order_discount_title'][$language_id];
			} elseif (!empty($this->config->get('total_order_discount_title')[$language_id])) {
				$data['discount_title'][$language_id] =
					$this->config->get('total_order_discount_title')[$language_id];
			} else {
				$data['discount_title'][$language_id] = '';
			}
		}

		if (isset($this->request->post['total_order_discount_sort_order'])) {
			$data['sort_order'] = $this->request->post['total_order_discount_sort_order'];
		} else {
			$data['sort_order'] = $this->config->get('total_order_discount_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/total/order_discount', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/total/order_discount')) {
			$this->error['permission'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['total_order_discount_title'] as $language_id => $discount_title) {
			if (utf8_strlen($discount_title) < 1 || utf8_strlen($discount_title) > 128) {
				$this->error['discount_title'] = $this->language->get('error_discount_title');
			}
		}

		return !$this->error;
	}
}
