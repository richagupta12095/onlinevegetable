<?php
class ControllerExtensionCiBlogCiBlogPost extends Controller {
	private $error = array();

	public function __construct($registry) {
		parent :: __construct($registry);
		$this->ciblog->buildTable();
	}

	public function index() {
		$this->load->language('extension/ciblog/ciblogpost');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/ciblogpost');

		$this->getList();
	}

	public function add() {
		$this->load->language('extension/ciblog/ciblogpost');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/ciblogpost');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciblog_ciblogpost->addCiBlogPost($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_available'])) {
				$url .= '&filter_date_available=' . $this->request->get['filter_date_available'];
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

			$this->response->redirect($this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/ciblog/ciblogpost');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/ciblogpost');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciblog_ciblogpost->editCiBlogPost($this->request->get['ciblog_post_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_available'])) {
				$url .= '&filter_date_available=' . $this->request->get['filter_date_available'];
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

			$this->response->redirect($this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/ciblog/ciblogpost');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/ciblogpost');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ciblog_post_id) {
				$this->model_extension_ciblog_ciblogpost->deleteCiBlogPost($ciblog_post_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_available'])) {
				$url .= '&filter_date_available=' . $this->request->get['filter_date_available'];
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

			$this->response->redirect($this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('extension/ciblog/ciblogpost');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/ciblogpost');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $ciblog_post_id) {
				$this->model_extension_ciblog_ciblogpost->copyCiBlogPost($ciblog_post_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_available'])) {
				$url .= '&filter_date_available=' . $this->request->get['filter_date_available'];
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

			$this->response->redirect($this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
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

		if (isset($this->request->get['filter_date_available'])) {
			$filter_date_available = $this->request->get['filter_date_available'];
		} else {
			$filter_date_available = null;
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

		if (isset($this->request->get['filter_image'])) {
			$filter_image = $this->request->get['filter_image'];
		} else {
			$filter_image = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
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

		if (isset($this->request->get['filter_date_available'])) {
			$url .= '&filter_date_available=' . $this->request->get['filter_date_available'];
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
			'href' => $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true)
		);

		$data['add'] = $this->url->link('extension/ciblog/ciblogpost/add', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		$data['copy'] = $this->url->link('extension/ciblog/ciblogpost/copy', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		$data['delete'] = $this->url->link('extension/ciblog/ciblogpost/delete', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);

		$data['ciblog_posts'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_status'   => $filter_status,
			'filter_date_available'   => $filter_date_available,
			'filter_date_added'   => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$ciblog_total = $this->model_extension_ciblog_ciblogpost->getTotalCiBlogPosts($filter_data);

		$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);

		$this->load->model('extension/ciblog/ciauthor');

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$ciauthor = $this->language->get('text_none');

			$ciauthor_info = $this->model_extension_ciblog_ciauthor->getCiAuthor($result['ciblog_author_id']);

			if($ciauthor_info) {
				$ciauthor = $ciauthor_info['name'];
			}


			$data['ciblog_posts'][] = array(
				'ciblog_post_id' => $result['ciblog_post_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'author'       => $ciauthor,
				'date_available' => $result['date_available'] != '0000-00-00 00:00:00' ? date($this->language->get('date_format_short'), strtotime($result['date_available'])) : '',
				'date_added' => $result['date_added'] != '0000-00-00 00:00:00' ? date($this->language->get('date_format_short'), strtotime($result['date_added'])) : '',
				'date_modified' => $result['date_modified'] != '0000-00-00 00:00:00' ? date($this->language->get('date_format_short'), strtotime($result['date_modified'])) : '',
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'       => $this->url->link('extension/ciblog/ciblogpost/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_author'] = $this->language->get('column_author');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_available'] = $this->language->get('column_date_available');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_available'] = $this->language->get('entry_date_available');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');
		$data['entry_image'] = $this->language->get('entry_image');

		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
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

		if (isset($this->request->get['filter_date_available'])) {
			$url .= '&filter_date_available=' . $this->request->get['filter_date_available'];
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

		$data['sort_name'] = $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=pd.name' . $url, true);
		$data['sort_author'] = $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=p.ciblog_author_id' . $url, true);
		$data['sort_status'] = $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=p.status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_available'])) {
			$url .= '&filter_date_available=' . $this->request->get['filter_date_available'];
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
		$pagination->total = $ciblog_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ciblog_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($ciblog_total - $this->config->get('config_limit_admin'))) ? $ciblog_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $ciblog_total, ceil($ciblog_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_status'] = $filter_status;
		$data['filter_date_available'] = $filter_date_available;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;


		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/ciblogpost_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['ciblog_post_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_none'] = $this->language->get('text_none');

		$data['column_comment_email'] = $this->language->get('column_comment_email');
		$data['column_comment_author'] = $this->language->get('column_comment_author');
		$data['column_comment_text'] = $this->language->get('column_comment_text');
		$data['column_comment_status'] = $this->language->get('column_comment_status');


		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_small_description'] = $this->language->get('entry_small_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_add_video_url'] = $this->language->get('entry_add_video_url');
		$data['entry_video_url'] = $this->language->get('entry_video_url');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_date_available'] = $this->language->get('entry_date_available');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['entry_author'] = $this->language->get('entry_author');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_additional_image'] = $this->language->get('entry_additional_image');
		$data['entry_alt'] = $this->language->get('entry_alt');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_cicategory'] = $this->language->get('entry_cicategory');
		$data['entry_related'] = $this->language->get('entry_related');
		$data['entry_related_product'] = $this->language->get('entry_related_product');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_layout'] = $this->language->get('entry_layout');

		$data['help_add_video_url'] = $this->language->get('help_add_video_url');
		$data['help_video_url'] = $this->language->get('help_video_url');
		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_cicategory'] = $this->language->get('help_cicategory');
		$data['help_author'] = $this->language->get('help_author');
		$data['help_related'] = $this->language->get('help_related');
		$data['help_related_product'] = $this->language->get('help_related_product');
		$data['help_tag'] = $this->language->get('help_tag');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_image_add'] = $this->language->get('button_image_add');
		$data['button_remove'] = $this->language->get('button_remove');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_links'] = $this->language->get('tab_links');
		$data['tab_design'] = $this->language->get('tab_design');
		$data['tab_comment'] = $this->language->get('tab_comment');

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

		if (isset($this->error['small_description'])) {
			$data['error_small_description'] = $this->error['small_description'];
		} else {
			$data['error_small_description'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['video_url'])) {
			$data['error_video_url'] = $this->error['video_url'];
		} else {
			$data['error_video_url'] = '';
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_available'])) {
			$url .= '&filter_date_available=' . $this->request->get['filter_date_available'];
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
			'href' => $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true)
		);

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		if (!isset($this->request->get['ciblog_post_id'])) {
			$data['action'] = $this->url->link('extension/ciblog/ciblogpost/add', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/ciblog/ciblogpost/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&ciblog_post_id=' . $this->request->get['ciblog_post_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);

		if (isset($this->request->get['ciblog_post_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$ciblog_info = $this->model_extension_ciblog_ciblogpost->getCiBlogPost($this->request->get['ciblog_post_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['languages'] = $this->ciblog->languages($data['languages']);

		if (isset($this->request->post['ciblog_post_description'])) {
			$data['ciblog_post_description'] = $this->request->post['ciblog_post_description'];
		} elseif (isset($this->request->get['ciblog_post_id'])) {
			$data['ciblog_post_description'] = $this->model_extension_ciblog_ciblogpost->getCiBlogPostDescriptions($this->request->get['ciblog_post_id']);
		} else {
			$data['ciblog_post_description'] = array();
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

		if (isset($this->request->post['ciblog_post_store'])) {
			$data['ciblog_post_store'] = $this->request->post['ciblog_post_store'];
		} elseif (isset($this->request->get['ciblog_post_id'])) {
			$data['ciblog_post_store'] = $this->model_extension_ciblog_ciblogpost->getCiBlogPostStores($this->request->get['ciblog_post_id']);
		} else {
			$data['ciblog_post_store'] = array(0);
		}

		if(VERSION <= '2.3.0.2') {
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
			} elseif (isset($ciblog_info['keyword'])) {
				$data['keyword'] = $ciblog_info['keyword'];
			} else {
				$data['keyword'] = '';
			}
		} else {
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
			} elseif (isset($this->request->get['ciblog_post_id'])) {
				$data['keyword'] = $this->model_extension_ciblog_ciblogpost->getCiBlogPostSeoUrls($this->request->get['ciblog_post_id']);
			} else {
				$data['keyword'] = array();
			}
		}


		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($ciblog_info['sort_order'])) {
			$data['sort_order'] = $ciblog_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}

		if (isset($this->request->post['add_video_url'])) {
			$data['add_video_url'] = $this->request->post['add_video_url'];
		} elseif (isset($ciblog_info['add_video_url'])) {
			$data['add_video_url'] = $ciblog_info['add_video_url'];
		} else {
			$data['add_video_url'] = 1;
		}

		if (isset($this->request->post['video_url'])) {
			$data['video_url'] = $this->request->post['video_url'];
		} elseif (isset($ciblog_info['video_url'])) {
			$data['video_url'] = $ciblog_info['video_url'];
		} else {
			$data['video_url'] = '';
		}

		if (isset($this->request->post['allow_comment'])) {
			$data['allow_comment'] = $this->request->post['allow_comment'];
		} elseif (isset($ciblog_info['allow_comment'])) {
			$data['allow_comment'] = $ciblog_info['allow_comment'];
		} else {
			$data['allow_comment'] = 1;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($ciblog_info['status'])) {
			$data['status'] = $ciblog_info['status'];
		} else {
			$data['status'] = 1;
		}

		if (isset($this->request->post['date_available'])) {
			$data['date_available'] = $this->request->post['date_available'];
		} elseif (isset($ciblog_info['date_available'])) {
			$data['date_available'] = ($ciblog_info['date_available'] != '0000-00-00' ? $ciblog_info['date_available'] : '');
		} else {
			$data['date_available'] = date('Y-m-d');
		}

		if (isset($this->request->post['date_added'])) {
			$data['date_added'] = $this->request->post['date_added'];
		} elseif (isset($ciblog_info['date_added'])) {
			$data['date_added'] = ($ciblog_info['date_added'] != '0000-00-00 00:00:00' ? $ciblog_info['date_added'] : '');
		} else {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if (isset($this->request->post['date_modified'])) {
			$data['date_modified'] = $this->request->post['date_modified'];
		} elseif (isset($ciblog_info['date_modified'])) {
			$data['date_modified'] = ($ciblog_info['date_modified'] != '0000-00-00 00:00:00' ? $ciblog_info['date_modified'] : '');
		} else {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		$this->load->model('extension/ciblog/ciauthor');

		if (isset($this->request->post['ciblog_author_id'])) {
			$data['ciblog_author_id'] = $this->request->post['ciblog_author_id'];
		} elseif (isset($ciblog_info['ciblog_author_id'])) {
			$data['ciblog_author_id'] = $ciblog_info['ciblog_author_id'];
		} else {
			$data['ciblog_author_id'] = 0;
		}

		if (isset($this->request->post['ciblog_author'])) {
			$data['ciblog_author'] = $this->request->post['ciblog_author'];
		} else {
			$ciblog_author_info = $this->model_extension_ciblog_ciauthor->getCiAuthor($data['ciblog_author_id']);

			if ($ciblog_author_info) {
				$data['ciblog_author'] = $ciblog_author_info['name'];
			} else {
				$data['ciblog_author'] = '';
			}
		}


		// Categories
		$this->load->model('extension/ciblog/cicategory');

		if (isset($this->request->post['ciblog_post_category'])) {
			$categories = $this->request->post['ciblog_post_category'];
		} elseif (isset($this->request->get['ciblog_post_id'])) {
			$categories = $this->model_extension_ciblog_ciblogpost->getCiBlogPostCategories($this->request->get['ciblog_post_id']);
		} else {
			$categories = array();
		}

		$data['ciblog_post_categories'] = array();

		foreach ($categories as $ciblog_category_id) {
			$category_info = $this->model_extension_ciblog_cicategory->getCiCategory($ciblog_category_id);

			if ($category_info) {
				$data['ciblog_post_categories'][] = array(
					'ciblog_category_id' => $category_info['ciblog_category_id'],
					'name'        => ($category_info['ciblogpath']) ? $category_info['ciblogpath'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		// Image
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (isset($ciblog_info['image'])) {
			$data['image'] = $ciblog_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (isset($ciblog_info) && is_file(DIR_IMAGE . $ciblog_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($ciblog_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		// Images
		if (isset($this->request->post['ciblog_post_image'])) {
			$ciblog_post_images = $this->request->post['ciblog_post_image'];
		} elseif (isset($this->request->get['ciblog_post_id'])) {
			$ciblog_post_images = $this->model_extension_ciblog_ciblogpost->getCiBlogPostImages($this->request->get['ciblog_post_id']);
		} else {
			$ciblog_post_images = array();
		}

		$data['ciblog_post_images'] = array();

		foreach ($ciblog_post_images as $ciblog_post_image) {
			if (is_file(DIR_IMAGE . $ciblog_post_image['image'])) {
				$image = $ciblog_post_image['image'];
				$thumb = $ciblog_post_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['ciblog_post_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order' => $ciblog_post_image['sort_order'],
				'info' => $ciblog_post_image['info'],
			);
		}

		if (isset($this->request->post['ciblog_post_related'])) {
			$ciblog_posts = $this->request->post['ciblog_post_related'];
		} elseif (isset($this->request->get['ciblog_post_id'])) {
			$ciblog_posts = $this->model_extension_ciblog_ciblogpost->getCiBlogPostRelated($this->request->get['ciblog_post_id']);
		} else {
			$ciblog_posts = array();
		}

		$data['ciblog_post_relateds'] = array();

		foreach ($ciblog_posts as $ciblog_post_id) {
			$related_info = $this->model_extension_ciblog_ciblogpost->getCiBlogPost($ciblog_post_id);

			if ($related_info) {
				$data['ciblog_post_relateds'][] = array(
					'ciblog_post_id' => $related_info['ciblog_post_id'],
					'name'       => $related_info['name']
				);
			}
		}

		$this->load->model('catalog/product');

		if (isset($this->request->post['ciblog_post_related_product'])) {
			$ciblog_products = $this->request->post['ciblog_post_related_product'];
		} elseif (isset($this->request->get['ciblog_post_id'])) {
			$ciblog_products = $this->model_extension_ciblog_ciblogpost->getCiBlogPostRelatedProducts($this->request->get['ciblog_post_id']);
		} else {
			$ciblog_products = array();
		}

		$data['ciblog_post_related_products'] = array();

		foreach ($ciblog_products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);

			if ($related_info) {
				$data['ciblog_post_related_products'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

		if (isset($this->request->post['ciblog_layout'])) {
			$data['ciblog_layout'] = $this->request->post['ciblog_layout'];
		} elseif (isset($this->request->get['ciblog_post_id'])) {
			$data['ciblog_layout'] = $this->model_extension_ciblog_ciblogpost->getCiBlogPostLayouts($this->request->get['ciblog_post_id']);
		} else {
			$data['ciblog_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('extension/ciblog/cicomment');
		$data['ciblog_post_comments'] = array();

		$comments = array();
		if (isset($this->request->get['ciblog_post_id'])) {
			$filter_data = array(
				'filter_ciblog_post_id' => $this->request->get['ciblog_post_id']
			);
			$comments = $this->model_extension_ciblog_cicomment->getCiComments($filter_data);
		}
		foreach ($comments as $key => $comment) {
			$data['ciblog_post_comments'][] = array(
				'author' => $comment['author'],
				'email' => $comment['email'],
				'text' => $comment['text'],
				'status' => $comment['status'],
				'status1' => $comment['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
			);
		}

		$data['text_editor'] = $this->ciblog->getTexEditorFiles($data);

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/ciblogpost_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/ciblog/ciblogpost')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['ciblog_post_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}

			if ((utf8_strlen($value['small_description']) < 3) || (utf8_strlen($value['small_description']) > 1000)) {
				$this->error['small_description'][$language_id] = $this->language->get('error_small_description');
			}
		}

		if ($this->request->post['add_video_url']) {
			if(empty($this->request->post['video_url'])) {
				$this->error['video_url'] = $this->language->get('error_video_url');
			}
		}


		if(VERSION <= '2.3.0.2') {
			if (utf8_strlen($this->request->post['keyword']) > 0) {
				$this->load->model('catalog/url_alias');

				$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

				if ($url_alias_info && isset($this->request->get['ciblog_post_id']) && $url_alias_info['query'] != 'ciblog_post_id=' . $this->request->get['ciblog_post_id']) {
					$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
				}

				if ($url_alias_info && !isset($this->request->get['ciblog_post_id'])) {
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
							if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['ciblog_post_id']) || (($seo_url['query'] != 'ciblog_post_id=' . $this->request->get['ciblog_post_id'])))) {
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
		if (!$this->user->hasPermission('modify', 'extension/ciblog/ciblogpost')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'extension/ciblog/ciblogpost')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/ciblog/ciblogpost');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'ciblog_post_id' => $result['ciblog_post_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}