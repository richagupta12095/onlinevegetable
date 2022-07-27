<?php
class ControllerExtensionModuleCiBlogCustom extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/ciblog_custom');

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
				$this->{$model_extension_module}->addModule('ciblog_custom', $this->request->post);
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
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['text_select_blog'] = $this->language->get('text_select_blog');
		$data['text_w'] = $this->language->get('text_w');
		$data['text_h'] = $this->language->get('text_h');

		$data['text_category'] = $this->language->get('text_category');
		$data['text_author'] = $this->language->get('text_author');
		$data['text_custom'] = $this->language->get('text_custom');
		$data['text_latest'] = $this->language->get('text_latest');
		$data['text_topview'] = $this->language->get('text_topview');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_author'] = $this->language->get('entry_author');
		$data['entry_custom'] = $this->language->get('entry_custom');
		$data['entry_latest'] = $this->language->get('entry_latest');
		$data['entry_topview'] = $this->language->get('entry_topview');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_category_sort_order'] = $this->language->get('entry_category_sort_order');
		$data['entry_author_sort_order'] = $this->language->get('entry_author_sort_order');
		$data['entry_custom_sort_order'] = $this->language->get('entry_custom_sort_order');
		$data['entry_latest_sort_order'] = $this->language->get('entry_latest_sort_order');
		$data['entry_topview_sort_order'] = $this->language->get('entry_topview_sort_order');
		$data['entry_blog_image'] = $this->language->get('entry_blog_image');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_category'] = $this->language->get('help_category');
		$data['help_author'] = $this->language->get('help_author');
		$data['help_custom'] = $this->language->get('help_custom');
		$data['help_limit'] = $this->language->get('help_limit');

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
				'href' => $this->url->link('extension/module/ciblog_custom', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/ciblog_custom', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/ciblog_custom', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true);
		} else {
			$data['action'] = $this->url->link('extension/module/ciblog_custom', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		$data['cancel'] = $this->url->link($this->ciblog->admin_extension_page_path, $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->{$model_extension_module}->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($module_info['name'])) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['ciblog_category'])) {
			$ciblog_categories = (array)$this->request->post['ciblog_category'];
		} elseif (isset($module_info['ciblog_category'])) {
			$ciblog_categories = (array)$module_info['ciblog_category'];
		} else {
			$ciblog_categories = array();
		}

		$data['ciblog_categories'] = array();
		$this->load->model('extension/ciblog/cicategory');
		foreach ($ciblog_categories as $ciblog_category_id) {
			$ciblog_category_info = $this->model_extension_ciblog_cicategory->getCiCategory($ciblog_category_id);
			if ($ciblog_category_info) {
				$data['ciblog_categories'][] = array(
					'ciblog_category_id' => $ciblog_category_info['ciblog_category_id'],
					'name' => $ciblog_category_info['name'],
				);
			}
		}

		if (isset($this->request->post['ciblog_author'])) {
			$ciblog_authors = (array)$this->request->post['ciblog_author'];
		} elseif (isset($module_info['ciblog_author'])) {
			$ciblog_authors = (array)$module_info['ciblog_author'];
		} else {
			$ciblog_authors = array();
		}

		$data['ciblog_authors'] = array();
		$this->load->model('extension/ciblog/ciauthor');
		foreach ($ciblog_authors as $ciblog_author_id) {
			$ciblog_author_info = $this->model_extension_ciblog_ciauthor->getCiAuthor($ciblog_author_id);
			if ($ciblog_author_info) {
				$data['ciblog_authors'][] = array(
					'ciblog_author_id' => $ciblog_author_info['ciblog_author_id'],
					'name' => $ciblog_author_info['name'],
				);
			}
		}

		if (isset($this->request->post['ciblog_custom'])) {
			$ciblog_customs = (array)$this->request->post['ciblog_custom'];
		} elseif (isset($module_info['ciblog_custom'])) {
			$ciblog_customs = (array)$module_info['ciblog_custom'];
		} else {
			$ciblog_customs = array();
		}


		$data['ciblog_customs'] = array();
		$this->load->model('extension/ciblog/ciblogpost');
		foreach ($ciblog_customs as $ciblog_post_id) {
			$ciblog_post_info = $this->model_extension_ciblog_ciblogpost->getCiBlogPost($ciblog_post_id);
			if ($ciblog_post_info) {
				$data['ciblog_customs'][] = array(
					'ciblog_post_id' => $ciblog_post_info['ciblog_post_id'],
					'name' => $ciblog_post_info['name'],
				);
			}
		}

		if (isset($this->request->post['ciblog_latest'])) {
			$data['ciblog_latest'] = $this->request->post['ciblog_latest'];
		} elseif (isset($module_info['ciblog_latest'])) {
			$data['ciblog_latest'] = $module_info['ciblog_latest'];
		} else {
			$data['ciblog_latest'] = 0;
		}

		if (isset($this->request->post['ciblog_topview'])) {
			$data['ciblog_topview'] = $this->request->post['ciblog_topview'];
		} elseif (isset($module_info['ciblog_topview'])) {
			$data['ciblog_topview'] = $module_info['ciblog_topview'];
		} else {
			$data['ciblog_topview'] = 0;
		}

		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (isset($module_info['limit'])) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (isset($module_info['width'])) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 438;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (isset($module_info['height'])) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 292;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($module_info['status'])) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = 0;
		}

		if (isset($this->request->post['ciblog_category_sort_order'])) {
			$data['ciblog_category_sort_order'] = $this->request->post['ciblog_category_sort_order'];
		} elseif (isset($module_info['ciblog_category_sort_order'])) {
			$data['ciblog_category_sort_order'] = $module_info['ciblog_category_sort_order'];
		} else {
			$data['ciblog_category_sort_order'] = '';
		}

		if (isset($this->request->post['ciblog_author_sort_order'])) {
			$data['ciblog_author_sort_order'] = $this->request->post['ciblog_author_sort_order'];
		} elseif (isset($module_info['ciblog_author_sort_order'])) {
			$data['ciblog_author_sort_order'] = $module_info['ciblog_author_sort_order'];
		} else {
			$data['ciblog_author_sort_order'] = '';
		}

		if (isset($this->request->post['ciblog_custom_sort_order'])) {
			$data['ciblog_custom_sort_order'] = $this->request->post['ciblog_custom_sort_order'];
		} elseif (isset($module_info['ciblog_custom_sort_order'])) {
			$data['ciblog_custom_sort_order'] = $module_info['ciblog_custom_sort_order'];
		} else {
			$data['ciblog_custom_sort_order'] = '';
		}

		if (isset($this->request->post['ciblog_latest_sort_order'])) {
			$data['ciblog_latest_sort_order'] = $this->request->post['ciblog_latest_sort_order'];
		} elseif (isset($module_info['ciblog_latest_sort_order'])) {
			$data['ciblog_latest_sort_order'] = $module_info['ciblog_latest_sort_order'];
		} else {
			$data['ciblog_latest_sort_order'] = '';
		}

		if (isset($this->request->post['ciblog_topview_sort_order'])) {
			$data['ciblog_topview_sort_order'] = $this->request->post['ciblog_topview_sort_order'];
		} elseif (isset($module_info['ciblog_topview_sort_order'])) {
			$data['ciblog_topview_sort_order'] = $module_info['ciblog_topview_sort_order'];
		} else {
			$data['ciblog_topview_sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/module/ciblog_custom', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ciblog_custom')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['width'] || !$this->request->post['height']) {
			$this->error['blog_image'] = $this->language->get('error_blog_image');
		}

		if(!isset($this->request->post['ciblog_category']) && !isset($this->request->post['ciblog_author']) && !isset($this->request->post['ciblog_custom']) && $this->request->post['ciblog_topview']==0 && $this->request->post['ciblog_latest']==0) {

			$this->error['warning'] = $this->language->get('error_blog');
		}

		return !$this->error;
	}
}