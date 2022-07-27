<?php
class ControllerExtensionModuleCiBlogTopView extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/ciblog_topview');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/ciblog/ciblog.css');
		if(VERSION >= '3.0.0.0') {
			$this->load->model('setting/module');
			$model_extension_module = 'model_setting_module';
		} else {
			$this->load->model('extension/module');
			$model_extension_module = 'model_extension_module';
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->{$model_extension_module}->addModule('ciblog_topview', $this->request->post);
			} else {
				$this->{$model_extension_module}->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->cache->delete('product');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link($this->ciblog->admin_extension_page_path, $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['text_w'] = $this->language->get('text_w');
		$data['text_h'] = $this->language->get('text_h');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_blog_image'] = $this->language->get('entry_blog_image');
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

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['blog_image'])) {
			$data['error_blog_image'] = $this->error['blog_image'];
		} else {
			$data['error_blog_image'] = '';
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

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/ciblog_topview', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/ciblog_topview', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/ciblog_topview', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true);
		} else {
			$data['action'] = $this->url->link('extension/module/ciblog_topview', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link($this->ciblog->admin_extension_page_path, $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->{$model_extension_module}->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 438;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 292;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/module/ciblog_topview', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ciblog_topview')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['width'] || !$this->request->post['height']) {
			$this->error['blog_image'] = $this->language->get('error_blog_image');
		}

		return !$this->error;
	}
}