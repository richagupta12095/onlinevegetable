<?php
class ControllerExtensionCiBlogCiAuthor extends Controller {
	private $error = array();

	public function __construct($registry) {
		parent :: __construct($registry);
		$this->ciblog->buildTable();
	}

	public function index() {
		$this->load->language('extension/ciblog/ciauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/ciauthor');

		$this->getList();
	}

	public function add() {
		$this->load->language('extension/ciblog/ciauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/ciauthor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciblog_ciauthor->addCiAuthor($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
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

			$this->response->redirect($this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/ciblog/ciauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/ciauthor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciblog_ciauthor->editCiAuthor($this->request->get['ciblog_author_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
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

			$this->response->redirect($this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/ciblog/ciauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/ciauthor');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ciblog_author_id) {
				$this->model_extension_ciblog_ciauthor->deleteCiAuthor($ciblog_author_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
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

			$this->response->redirect($this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_author'])) {
			$filter_author = $this->request->get['filter_author'];
		} else {
			$filter_author = null;
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

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true)
		);

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		$data['add'] = $this->url->link('extension/ciblog/ciauthor/add', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		$data['delete'] = $this->url->link('extension/ciblog/ciauthor/delete', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);

		$data['ciauthors'] = array();

		$filter_data = array(
			'filter_author'  => $filter_author,
			'filter_status'  => $filter_status,
			'filter_date_added'  => $filter_date_added,
			'filter_date_modified'  => $filter_date_modified,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$ciauthor_total = $this->model_extension_ciblog_ciauthor->getTotalCiAuthors($filter_data);

		$results = $this->model_extension_ciblog_ciauthor->getCiAuthors($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$data['ciauthors'][] = array(
				'ciblog_author_id' => $result['ciblog_author_id'],
				'name'            => $result['name'],
				'image'      => $image,
				'sort_order'      => $result['sort_order'],
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added' => $result['date_added'] != '0000-00-00 00:00:00' ? date($this->language->get('date_format_short'), strtotime($result['date_added'])) : '',
				'date_modified' => $result['date_modified'] != '0000-00-00 00:00:00' ? date($this->language->get('date_format_short'), strtotime($result['date_modified'])) : '',
				'edit'            => $this->url->link('extension/ciblog/ciauthor/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&ciblog_author_id=' . $result['ciblog_author_id'] . $url, true)
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

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
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

		$data['sort_name'] = $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=pd.name' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=p.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=p.date_modified' . $url, true);
		$data['sort_sort_order'] = $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=p.sort_order' . $url, true);
		$data['sort_status'] = $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=p.status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
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
		$pagination->total = $ciauthor_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ciauthor_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($ciauthor_total - $this->config->get('config_limit_admin'))) ? $ciauthor_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $ciauthor_total, ceil($ciauthor_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['filter_author'] = $filter_author;
		$data['filter_status'] = $filter_status;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		$this->response->setOutput($this->ciblog->view('extension/ciblog/ciauthor_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['ciblog_author_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_alt'] = $this->language->get('entry_alt');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_keyword'] = $this->language->get('help_keyword');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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

		$url = '';

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true)
		);

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		if (!isset($this->request->get['ciblog_author_id'])) {
			$data['action'] = $this->url->link('extension/ciblog/ciauthor/add', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/ciblog/ciauthor/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&ciblog_author_id=' . $this->request->get['ciblog_author_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);

		if (isset($this->request->get['ciblog_author_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$ciauthor_info = $this->model_extension_ciblog_ciauthor->getCiAuthor($this->request->get['ciblog_author_id']);
		}



		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['languages'] = $this->ciblog->languages($data['languages']);

		if (isset($this->request->post['ciauthor_description'])) {
			$data['ciauthor_description'] = $this->request->post['ciauthor_description'];
		} elseif (isset($this->request->get['ciblog_author_id'])) {
			$data['ciauthor_description'] = $this->model_extension_ciblog_ciauthor->getCiBlogAuthorDescriptions($this->request->get['ciblog_author_id']);
		} else {
			$data['ciauthor_description'] = array();
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

		if (isset($this->request->post['ciauthor_store'])) {
			$data['ciauthor_store'] = $this->request->post['ciauthor_store'];
		} elseif (isset($this->request->get['ciblog_author_id'])) {
			$data['ciauthor_store'] = $this->model_extension_ciblog_ciauthor->getCiAuthorStores($this->request->get['ciblog_author_id']);
		} else {
			$data['ciauthor_store'] = array(0);
		}

		if(VERSION <= '2.3.0.2') {
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
			} elseif (!empty($ciauthor_info)) {
				$data['keyword'] = $ciauthor_info['keyword'];
			} else {
				$data['keyword'] = '';
			}
		} else {
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
			} elseif (isset($this->request->get['ciblog_author_id'])) {
				$data['keyword'] = $this->model_extension_ciblog_ciauthor->getCiAuthorSeoUrls($this->request->get['ciblog_author_id']);
			} else {
				$data['keyword'] = array();
			}
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($ciauthor_info)) {
			$data['image'] = $ciauthor_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($ciauthor_info) && is_file(DIR_IMAGE . $ciauthor_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($ciauthor_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($ciauthor_info)) {
			$data['sort_order'] = $ciauthor_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->post['date_added'])) {
			$data['date_added'] = $this->request->post['date_added'];
		} elseif (isset($ciauthor_info['date_added'])) {
			$data['date_added'] = ($ciauthor_info['date_added'] != '0000-00-00 00:00:00' ? $ciauthor_info['date_added'] : '');
		} else {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if (isset($this->request->post['date_modified'])) {
			$data['date_modified'] = $this->request->post['date_modified'];
		} elseif (isset($ciauthor_info['date_modified'])) {
			$data['date_modified'] = ($ciauthor_info['date_modified'] != '0000-00-00 00:00:00' ? $ciauthor_info['date_modified'] : '');
		} else {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($ciauthor_info)) {
			$data['status'] = $ciauthor_info['status'];
		} else {
			$data['status'] = true;
		}

		$data['text_editor'] = $this->ciblog->getTexEditorFiles($data);

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/ciauthor_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/ciblog/ciauthor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		foreach ($this->request->post['ciauthor_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 64)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 64)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}


		if(VERSION <= '2.3.0.2') {
			if (utf8_strlen($this->request->post['keyword']) > 0) {

				$this->load->model('catalog/url_alias');

				$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

				if ($url_alias_info && isset($this->request->get['ciblog_author_id']) && $url_alias_info['query'] != 'ciblog_author_id=' . $this->request->get['ciblog_author_id']) {
					$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
				}

				if ($url_alias_info && !isset($this->request->get['ciblog_author_id'])) {
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
							if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['ciblog_author_id']) || (($seo_url['query'] != 'ciblog_author_id=' . $this->request->get['ciblog_author_id'])))) {
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
		if (!$this->user->hasPermission('modify', 'extension/ciblog/ciauthor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/ciblog/ciauthor');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_ciblog_ciauthor->getCiAuthors($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'ciblog_author_id' => $result['ciblog_author_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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