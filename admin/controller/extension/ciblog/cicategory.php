<?php
class ControllerExtensionCiBlogCiCategory extends Controller {
	private $error = array();

	public function __construct($registry) {
		parent :: __construct($registry);
		$this->ciblog->buildTable();
	}

	public function index() {
		$this->load->language('extension/ciblog/cicategory');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/cicategory');

		$this->getList();
	}

	public function add() {
		$this->load->language('extension/ciblog/cicategory');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/cicategory');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciblog_cicategory->addCiCategory($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

			$this->response->redirect($this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/ciblog/cicategory');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/cicategory');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciblog_cicategory->editCiCategory($this->request->get['ciblog_category_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

			$this->response->redirect($this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/ciblog/cicategory');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/cicategory');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ciblog_category_id) {
				$this->model_extension_ciblog_cicategory->deleteCiCategory($ciblog_category_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

			$this->response->redirect($this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getList();
	}

	public function repair() {
		$this->load->language('extension/ciblog/cicategory');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/cicategory');

		if ($this->validateRepair()) {
			$this->model_extension_ciblog_cicategory->repairCiCategories();

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

			$this->response->redirect($this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
			'href' => $this->url->link('common/dashboard', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true)
		);

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		$data['add'] = $this->url->link('extension/ciblog/cicategory/add', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		$data['delete'] = $this->url->link('extension/ciblog/cicategory/delete', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		$data['repair'] = $this->url->link('extension/ciblog/cicategory/repair', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);

		$data['cicategories'] = array();

		$filter_data = array(
			'filter_name'  => $filter_name,
			'filter_status'  => $filter_status,
			'filter_date_added'  => $filter_date_added,
			'filter_date_modified'  => $filter_date_modified,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$cicategory_total = $this->model_extension_ciblog_cicategory->getTotalCiCategories($filter_data);

		$results = $this->model_extension_ciblog_cicategory->getCiCategories($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$data['cicategories'][] = array(
				'ciblog_category_id' => $result['ciblog_category_id'],
				'name'        => $result['name'],
				'image'      => $image,
				'sort_order'  => $result['sort_order'],
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added' => $result['date_added'] != '0000-00-00 00:00:00' ? date($this->language->get('date_format_short'), strtotime($result['date_added'])) : '',
				'date_modified' => $result['date_modified'] != '0000-00-00 00:00:00' ? date($this->language->get('date_format_short'), strtotime($result['date_modified'])) : '',
				'edit'        => $this->url->link('extension/ciblog/cicategory/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&ciblog_category_id=' . $result['ciblog_category_id'] . $url, true),
				'delete'      => $this->url->link('extension/ciblog/cicategory/delete', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&ciblog_category_id=' . $result['ciblog_category_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_rebuild'] = $this->language->get('button_rebuild');
		$data['button_filter'] = $this->language->get('button_filter');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=name' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=c1.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=c1.date_modified' . $url, true);
		$data['sort_sort_order'] = $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=c1.sort_order' . $url, true);
		$data['sort_status'] = $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=c1.status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $cicategory_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($cicategory_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cicategory_total - $this->config->get('config_limit_admin'))) ? $cicategory_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cicategory_total, ceil($cicategory_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['filter_name'] = $filter_name;
		$data['filter_status'] = $filter_status;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/cicategory_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['ciblog_category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_parent'] = $this->language->get('entry_parent');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_alt'] = $this->language->get('entry_alt');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_layout'] = $this->language->get('entry_layout');

		$data['help_keyword'] = $this->language->get('help_keyword');


		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_design'] = $this->language->get('tab_design');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if(VERSION <= '2.3.0.2') {
			if (isset($this->error['keyword'])) {
				$data['error_keyword'] = $this->error['keyword'];
			} else {
				$data['error_keyword'] = '';
			}
		} else {
			if (isset($this->error['keyword'])) {
				$data['error_keyword'] = $this->error['keyword'];
			} else {
				$data['error_keyword'] = array();
			}
		}

		if (isset($this->error['parent'])) {
			$data['error_parent'] = $this->error['parent'];
		} else {
			$data['error_parent'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
			'href' => $this->url->link('common/dashboard', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true)
		);

		if (!isset($this->request->get['ciblog_category_id'])) {
			$data['action'] = $this->url->link('extension/ciblog/cicategory/add', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/ciblog/cicategory/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&ciblog_category_id=' . $this->request->get['ciblog_category_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);

		if (isset($this->request->get['ciblog_category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$cicategory_info = $this->model_extension_ciblog_cicategory->getCiCategory($this->request->get['ciblog_category_id']);
		}

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['languages'] = $this->ciblog->languages($data['languages']);

		if (isset($this->request->post['cicategory_description'])) {
			$data['cicategory_description'] = $this->request->post['cicategory_description'];
		} elseif (isset($this->request->get['ciblog_category_id'])) {
			$data['cicategory_description'] = $this->model_extension_ciblog_cicategory->getCiCategoryDescriptions($this->request->get['ciblog_category_id']);
		} else {
			$data['cicategory_description'] = array();
		}

		if (isset($this->request->post['ciblogpath'])) {
			$data['ciblogpath'] = $this->request->post['ciblogpath'];
		} elseif (!empty($cicategory_info)) {
			$data['ciblogpath'] = $cicategory_info['ciblogpath'];
		} else {
			$data['ciblogpath'] = '';
		}

		if (isset($this->request->post['parent_id'])) {
			$data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($cicategory_info)) {
			$data['parent_id'] = $cicategory_info['parent_id'];
		} else {
			$data['parent_id'] = 0;
		}

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();
		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => '0',
			'name' => $this->language->get('text_default'),
		);
		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name' => $store['name'],
			);
		}

		if (isset($this->request->post['cicategory_store'])) {
			$data['cicategory_store'] = $this->request->post['cicategory_store'];
		} elseif (isset($this->request->get['ciblog_category_id'])) {
			$data['cicategory_store'] = $this->model_extension_ciblog_cicategory->getCiCategoryStores($this->request->get['ciblog_category_id']);
		} else {
			$data['cicategory_store'] = array(0);
		}

		if(VERSION <= '2.3.0.2') {
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
			} elseif (!empty($cicategory_info)) {
				$data['keyword'] = $cicategory_info['keyword'];
			} else {
				$data['keyword'] = '';
			}
		} else {
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
			} elseif (isset($this->request->get['ciblog_category_id'])) {
				$data['keyword'] = $this->model_extension_ciblog_cicategory->getCiCategorySeoUrls($this->request->get['ciblog_category_id']);
			} else {
				$data['keyword'] = array();
			}
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($cicategory_info)) {
			$data['image'] = $cicategory_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($cicategory_info) && is_file(DIR_IMAGE . $cicategory_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($cicategory_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($cicategory_info)) {
			$data['sort_order'] = $cicategory_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (isset($this->request->post['date_added'])) {
			$data['date_added'] = $this->request->post['date_added'];
		} elseif (!empty($cicategory_info)) {
			$data['date_added'] = ($cicategory_info['date_added'] != '0000-00-00 00:00:00' ? $cicategory_info['date_added'] : '');
		} else {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if (isset($this->request->post['date_modified'])) {
			$data['date_modified'] = $this->request->post['date_modified'];
		} elseif (!empty($cicategory_info)) {
			$data['date_modified'] = ($cicategory_info['date_modified'] != '0000-00-00 00:00:00' ? $cicategory_info['date_modified'] : '');
		} else {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($cicategory_info)) {
			$data['status'] = $cicategory_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['cicategory_layout'])) {
			$data['cicategory_layout'] = $this->request->post['cicategory_layout'];
		} elseif (isset($this->request->get['ciblog_category_id'])) {
			$data['cicategory_layout'] = $this->model_extension_ciblog_cicategory->getCiCategoryLayouts($this->request->get['ciblog_category_id']);
		} else {
			$data['cicategory_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['text_editor'] = $this->ciblog->getTexEditorFiles($data);

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/cicategory_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/ciblog/cicategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['cicategory_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if (isset($this->request->get['ciblog_category_id']) && $this->request->post['parent_id']) {
			$results = $this->model_extension_ciblog_cicategory->getCiCategoryPath($this->request->post['parent_id']);

			foreach ($results as $result) {
				if ($result['path_id'] == $this->request->get['ciblog_category_id']) {
					$this->error['parent'] = $this->language->get('error_parent');

					break;
				}
			}
		}


		if(VERSION <= '2.3.0.2') {
			if (utf8_strlen($this->request->post['keyword']) > 0) {
				$this->load->model('catalog/url_alias');

				$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

				if ($url_alias_info && isset($this->request->get['ciblog_category_id']) && $url_alias_info['query'] != 'ciblog_category_id=' . $this->request->get['ciblog_category_id']) {
					$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
				}

				if ($url_alias_info && !isset($this->request->get['ciblog_category_id'])) {
					$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
				}
			}
		} else {

			$this->load->model('design/seo_url');

			foreach ($this->request->post['keyword'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						if (count(array_keys($language, $keyword)) > 1) {
							$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
						}

						$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);

						foreach ($seo_urls as $seo_url) {
							if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['ciblog_category_id']) || (($seo_url['query'] != 'ciblog_category_id=' . $this->request->get['ciblog_category_id'])))) {
								$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');

								break;
							}
						}
					}
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/ciblog/cicategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateRepair() {
		if (!$this->user->hasPermission('modify', 'extension/ciblog/cicategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/ciblog/cicategory');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_ciblog_cicategory->getCiCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'ciblog_category_id' => $result['ciblog_category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
