<?php
class ControllerSmsHistory extends Controller {
	private $error = array();

	public function index() {
		$data = $this->load->language('sms/history');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sms/opc_sms');

		$filter_array = array('filter_id', 'filter_mobile', 'filter_message', 'filter_response', 'filter_date');

		foreach ($filter_array as $value) {
		  if (isset($this->request->get[$value])) {
		    $$value = $this->request->get[$value];
		  } else {
		    $$value = '';
		  }
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		foreach ($filter_array as $value) {
		  if (isset($this->request->get[$value])) {
		    $url .= '&' . $value . '=' . urlencode(html_entity_decode($this->request->get[$value], ENT_QUOTES, 'UTF-8'));
		  }
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sms/history', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['messages'] = array();

		$filter_data = array(
			'filter_id'				=> $filter_id,
			'filter_mobile'		=> $filter_mobile,
			'filter_message'	=> $filter_message,
			'filter_response'	=> $filter_response,
			'filter_date'			=> $filter_date,
			'sort'      			=> $sort,
			'order'    				=> $order,
			'start'     			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'    				=> $this->config->get('config_limit_admin')
		);

		$sms_total = $this->model_sms_opc_sms->getTotalSMS($filter_data);

		$data['messages'] = $this->model_sms_opc_sms->getSMS($filter_data);

		$data['user_token'] = $this->session->data['user_token'];

		$data['cancel'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);

		$data['delete'] = $this->url->link('sms/history/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['resend'] = $this->url->link('sms/history/resend', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
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

		$url = '';

		foreach ($filter_array as $value) {
		  if (isset($this->request->get[$value])) {
		    $url .= '&' . $value . '=' . urlencode(html_entity_decode($this->request->get[$value], ENT_QUOTES, 'UTF-8'));
		  }
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$url = '';

		foreach ($filter_array as $value) {
		  if (isset($this->request->get[$value])) {
		    $url .= '&' . $value . '=' . urlencode(html_entity_decode($this->request->get[$value], ENT_QUOTES, 'UTF-8'));
		  }
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $sms_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sms/history', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($sms_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($sms_total - $this->config->get('config_limit_admin'))) ? $sms_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $sms_total, ceil($sms_total / $this->config->get('config_limit_admin')));

		foreach ($filter_array as $value) {
		  $data[$value] = $$value;
		}

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sms/history', $data));
	}

	public function delete() {
	  $this->load->language('sms/history');

	  $this->document->setTitle($this->language->get('heading_title'));

	  $this->load->model('sms/opc_sms');

	  $url = '';

	  if (isset($this->request->post['selected']) && $this->validateDelete()) {
	    $this->model_sms_opc_sms->deleteSMS(implode(',', $this->request->post['selected']));

	    $this->session->data['success'] = $this->language->get('text_success_delete');

	    $filter_array = array('filter_id', 'filter_mobile', 'filter_message', 'filter_response', 'filter_date');

	    foreach ($filter_array as $value) {
			  if (isset($this->request->get[$value])) {
			    $url .= '&' . $value . '=' . urlencode(html_entity_decode($this->request->get[$value], ENT_QUOTES, 'UTF-8'));
			  }
			}
	  } else {
			$this->session->data['warning'] = $this->language->get('error_delete');
		}

	  $this->response->redirect($this->url->link('sms/history', 'user_token=' . $this->session->data['user_token'] . $url, true));
	}

	public function resend() {
	  $this->load->language('sms/history');

	  $this->document->setTitle($this->language->get('heading_title'));

	  $this->load->model('sms/opc_sms');

	  $url = '';

	  if (isset($this->request->post['selected'])) {

			$this->registry->set('sms', new Sms($this->registry));

			$this->sms->resendSMS($this->request->post['selected']);

	    $this->session->data['success'] = $this->language->get('text_success_resend');

	    $filter_array = array('filter_id', 'filter_mobile', 'filter_message', 'filter_response', 'filter_date');

	    foreach ($filter_array as $value) {
			  if (isset($this->request->get[$value])) {
			    $url .= '&' . $value . '=' . urlencode(html_entity_decode($this->request->get[$value], ENT_QUOTES, 'UTF-8'));
			  }
			}
	  } else {
			$this->session->data['warning'] = $this->language->get('error_resend');
		}

	  $this->response->redirect($this->url->link('sms/history', 'user_token=' . $this->session->data['user_token'] . $url, true));
	}

	protected function validateDelete() {
	  if (!$this->user->hasPermission('modify', 'sms/history')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	  }

	  return !$this->error;
	}
}
