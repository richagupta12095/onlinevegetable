<?php
class ControllerSmsTemplate extends Controller {
	private $error = array();

	public function index() {
		$data = $this->load->language('sms/template');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sms/opc_sms');

		$filter_array = array('filter_id', 'filter_name', 'filter_message');

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
			'href' => $this->url->link('sms/template', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['messages'] = array();

		$filter_data = array(
			'filter_id'				=> $filter_id,
			'filter_name'			=> $filter_name,
			'filter_message'	=> $filter_message,
			'sort'      			=> $sort,
			'order'    				=> $order,
			'start'     			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'    				=> $this->config->get('config_limit_admin')
		);

		$templates_total = $this->model_sms_opc_sms->getTotalTemplates($filter_data);

		$data['templates'] = $this->model_sms_opc_sms->getTemplates($filter_data);

		$data['user_token'] = $this->session->data['user_token'];

		$data['cancel'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);

		$data['delete'] = $this->url->link('sms/template/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['add'] = $this->url->link('sms/template/add', 'user_token=' . $this->session->data['user_token'] . $url, true);

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
		$pagination->total = $templates_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sms/template', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($templates_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($templates_total - $this->config->get('config_limit_admin'))) ? $templates_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $templates_total, ceil($templates_total / $this->config->get('config_limit_admin')));

		foreach ($filter_array as $value) {
		  $data[$value] = $$value;
		}

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sms/template', $data));
	}

	public function delete() {
	  $this->load->language('sms/template');

	  $this->document->setTitle($this->language->get('heading_title'));

	  $this->load->model('sms/opc_sms');

	  $url = '';

	  if (isset($this->request->post['selected']) && $this->validateDelete()) {
	    $this->model_sms_opc_sms->deleteTemplate(implode(',', $this->request->post['selected']));

	    $this->session->data['success'] = $this->language->get('text_success_delete');

	    $filter_array = array('filter_id', 'filter_name', 'filter_message');

	    foreach ($filter_array as $value) {
			  if (isset($this->request->get[$value])) {
			    $url .= '&' . $value . '=' . urlencode(html_entity_decode($this->request->get[$value], ENT_QUOTES, 'UTF-8'));
			  }
			}
	  } else {
			$this->session->data['warning'] = $this->language->get('error_delete');
		}

	  $this->response->redirect($this->url->link('sms/template', 'user_token=' . $this->session->data['user_token'] . $url, true));
	}

	public function add() {
	  $data = $this->load->language('sms/template');

	  $this->document->setTitle($this->language->get('heading_title'));

	  $this->load->model('sms/opc_sms');

		$url = '';

		if ($this->request->post) {
			$this->request->post = array_map('trim', $this->request->post);
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sms_opc_sms->editTemplate($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('sms/template', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['message'])) {
			$data['error_message'] = $this->error['message'];
		} else {
			$data['error_message'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sms/template', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('sms/template/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('sms/template/add', 'user_token=' . $this->session->data['user_token'] . '&id=' . $this->request->get['id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('sms/template', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$template_info = $this->model_sms_opc_sms->getTemplates(array('filter_id' => $this->request->get['id']))[0];
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($template_info)) {
			$data['name'] = $template_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['message'])) {
		  $data['message'] = $this->request->post['message'];
		} elseif (!empty($template_info)) {
		  $data['message'] = $template_info['message'];
		} else {
		  $data['message'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sms/template_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sms/template')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['message']) < 3)) {
			$this->error['message'] = $this->language->get('error_message');
		}

		return !$this->error;
	}

	protected function validateDelete() {
	  if (!$this->user->hasPermission('modify', 'sms/template')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	  }

	  return !$this->error;
	}
}
