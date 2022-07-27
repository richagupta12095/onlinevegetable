<?php
class ControllerExtensionCiBlogCiComment extends Controller {
	private $error = array();

	public function __construct($registry) {
		parent :: __construct($registry);
		$this->ciblog->buildTable();
	}

	public function index() {
		$this->load->language('extension/ciblog/cicomment');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/cicomment');

		$this->getList();
	}

	public function add() {
		$this->load->language('extension/ciblog/cicomment');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/cicomment');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciblog_cicomment->addCiComment($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_ciblog_post'])) {
				$url .= '&filter_ciblog_post=' . urlencode(html_entity_decode($this->request->get['filter_ciblog_post'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_rating'])) {
				$url .= '&filter_rating=' . $this->request->get['filter_rating'];
			}
			if (isset($this->request->get['filter_language_id'])) {
				$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
			}
			if (isset($this->request->get['filter_store_id'])) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
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

			$this->response->redirect($this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/ciblog/cicomment');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/cicomment');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciblog_cicomment->editCiComment($this->request->get['ciblog_comment_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_ciblog_post'])) {
				$url .= '&filter_ciblog_post=' . urlencode(html_entity_decode($this->request->get['filter_ciblog_post'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_rating'])) {
				$url .= '&filter_rating=' . $this->request->get['filter_rating'];
			}
			if (isset($this->request->get['filter_language_id'])) {
				$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
			}
			if (isset($this->request->get['filter_store_id'])) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
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

			$this->response->redirect($this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/ciblog/cicomment');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciblog/cicomment');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ciblog_comment_id) {
				$this->model_extension_ciblog_cicomment->deleteCiComment($ciblog_comment_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_ciblog_post'])) {
				$url .= '&filter_ciblog_post=' . urlencode(html_entity_decode($this->request->get['filter_ciblog_post'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_rating'])) {
				$url .= '&filter_rating=' . $this->request->get['filter_rating'];
			}
			if (isset($this->request->get['filter_language_id'])) {
				$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
			}
			if (isset($this->request->get['filter_store_id'])) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
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

			$this->response->redirect($this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_ciblog_post'])) {
			$filter_ciblog_post = $this->request->get['filter_ciblog_post'];
		} else {
			$filter_ciblog_post = null;
		}

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
		if (isset($this->request->get['filter_rating'])) {
			$filter_rating = $this->request->get['filter_rating'];
		} else {
			$filter_rating = null;
		}
		if (isset($this->request->get['filter_language_id'])) {
			$filter_language_id = $this->request->get['filter_language_id'];
		} else {
			$filter_language_id = null;
		}
		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = $this->request->get['filter_store_id'];
		} else {
			$filter_store_id = null;
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

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_ciblog_post'])) {
			$url .= '&filter_ciblog_post=' . urlencode(html_entity_decode($this->request->get['filter_ciblog_post'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}
		if (isset($this->request->get['filter_language_id'])) {
			$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
		}
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
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
			'href' => $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true)
		);

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['languages'] = $this->ciblog->languages($data['languages']);

		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => '0',
			'name' => $this->language->get('text_default')
		);
		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name' => $store['name']
			);
		}

		$data['add'] = $this->url->link('extension/ciblog/cicomment/add', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		$data['delete'] = $this->url->link('extension/ciblog/cicomment/delete', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);

		$data['cicomments'] = array();

		$filter_data = array(
			'filter_ciblog_post'    => $filter_ciblog_post,
			'filter_author'     => $filter_author,
			'filter_status'     => $filter_status,
			'filter_rating'     => $filter_rating,
			'filter_language_id'     => $filter_language_id,
			'filter_store_id'     => $filter_store_id,
			'filter_date_added' => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$cicomment_total = $this->model_extension_ciblog_cicomment->getTotalCiComments($filter_data);

		$results = $this->model_extension_ciblog_cicomment->getCiComments($filter_data);

		$text_default =

		$this->load->model('setting/store');
		$this->load->model('localisation/language');
		$this->load->model('extension/ciblog/ciblogpost');

		foreach ($results as $result) {

			$store = $this->language->get('text_default');
			if ($result['store_id']) {
				$store_info = $this->model_setting_store->getStore($result['store_id']);
				if ($store_info) {
					$store = $store_info['name'];
				}
			}

			$language = $this->language->get('text_no_language');
			if ($result['language_id']) {
				$language_info = $this->model_localisation_language->getLanguage($result['language_id']);
				if ($language_info) {
					$language = $language_info['name'];
				}
			}

			$ciblog_post = '';
			$ciblog_post_href = '';
			if ($result['ciblog_post_id']) {
				$ciblog_post_info = $this->model_extension_ciblog_ciblogpost->getCiBlogPost($result['ciblog_post_id']);
				if ($ciblog_post_info) {
					$ciblog_post = $ciblog_post_info['name'];
					$ciblog_post_href = $this->url->link('extension/ciblog/ciblogpost/edit', $this->ciblog->octoken.'='.$this->session->data[$this->ciblog->octoken].'&ciblog_post_id='.$ciblog_post_info['ciblog_post_id'] , true);
				}
			}

			$data['cicomments'][] = array(
				'ciblog_comment_id'  => $result['ciblog_comment_id'],
				'rating'       => (int)$result['rating'],
				'email'       => $result['email'],
				'author'     => $result['author'],
				'rating'     => $result['rating'],
				'ciblog_post'     => $ciblog_post,
				'ciblog_post_href'     => $ciblog_post_href,
				'language'     => $language,
				'store'     => $store,
				'status'     => $result['status'],
				'status1'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added' => $result['date_added'] != '0000-00-00 00:00:00' ? date($this->language->get('date_format_short'), strtotime($result['date_added'])) : '',
				'date_modified' => $result['date_modified'] != '0000-00-00 00:00:00' ? date($this->language->get('date_format_short'), strtotime($result['date_modified'])) : '',
				'edit'       => $this->url->link('extension/ciblog/cicomment/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&ciblog_comment_id=' . $result['ciblog_comment_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['column_ciblog_post'] = $this->language->get('column_ciblog_post');
		$data['column_author'] = $this->language->get('column_author');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_rating'] = $this->language->get('column_rating');
		$data['column_language'] = $this->language->get('column_language');
		$data['column_store'] = $this->language->get('column_store');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_ciblog_post'] = $this->language->get('entry_ciblog_post');
		$data['entry_author'] = $this->language->get('entry_author');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

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

		if (isset($this->request->get['filter_ciblog_post'])) {
			$url .= '&filter_ciblog_post=' . urlencode(html_entity_decode($this->request->get['filter_ciblog_post'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}
		if (isset($this->request->get['filter_language_id'])) {
			$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
		}
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
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

		$data['sort_ciblog_post'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=pd.name' . $url, true);
		$data['sort_email'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=r.email' . $url, true);
		$data['sort_author'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=r.author' . $url, true);
		$data['sort_rating'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=r.rating' . $url, true);
		$data['sort_language_id'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=r.language_id' . $url, true);
		$data['sort_store_id'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=r.store_id' . $url, true);
		$data['sort_status'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=r.status' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=r.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&sort=r.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_ciblog_post'])) {
			$url .= '&filter_ciblog_post=' . urlencode(html_entity_decode($this->request->get['filter_ciblog_post'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}
		if (isset($this->request->get['filter_language_id'])) {
			$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
		}
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
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
		$pagination->total = $cicomment_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($cicomment_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cicomment_total - $this->config->get('config_limit_admin'))) ? $cicomment_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cicomment_total, ceil($cicomment_total / $this->config->get('config_limit_admin')));

		$data['filter_ciblog_post'] = $filter_ciblog_post;
		$data['filter_author'] = $filter_author;
		$data['filter_status'] = $filter_status;
		$data['filter_rating'] = $filter_rating;
		$data['filter_language_id'] = $filter_language_id;
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/cicomment_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['ciblog_comment_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_ciblog_post'] = $this->language->get('entry_ciblog_post');
		$data['entry_author'] = $this->language->get('entry_author');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_text'] = $this->language->get('entry_text');

		$data['help_ciblog_post'] = $this->language->get('help_ciblog_post');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['ciblog_post'])) {
			$data['error_ciblog_post'] = $this->error['ciblog_post'];
		} else {
			$data['error_ciblog_post'] = '';
		}

		if (isset($this->error['author'])) {
			$data['error_author'] = $this->error['author'];
		} else {
			$data['error_author'] = '';
		}

		if (isset($this->error['rating'])) {
			$data['error_rating'] = $this->error['rating'];
		} else {
			$data['error_rating'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['text'])) {
			$data['error_text'] = $this->error['text'];
		} else {
			$data['error_text'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_ciblog_post'])) {
			$url .= '&filter_ciblog_post=' . urlencode(html_entity_decode($this->request->get['filter_ciblog_post'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_rating'])) {
			$url .= '&filter_rating=' . $this->request->get['filter_rating'];
		}
		if (isset($this->request->get['filter_language_id'])) {
			$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
		}
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
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
			'href' => $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true)
		);

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		if (!isset($this->request->get['ciblog_comment_id'])) {
			$data['action'] = $this->url->link('extension/ciblog/cicomment/add', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/ciblog/cicomment/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&ciblog_comment_id=' . $this->request->get['ciblog_comment_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . $url, true);

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['languages'] = $this->ciblog->languages($data['languages']);

		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => '0',
			'name' => $this->language->get('text_default')
		);
		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name' => $store['name']
			);
		}

		if (isset($this->request->get['ciblog_comment_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$cicomment_info = $this->model_extension_ciblog_cicomment->getCiComment($this->request->get['ciblog_comment_id']);
		}

		$this->load->model('extension/ciblog/ciblogpost');

		if (isset($this->request->post['ciblog_post_id'])) {
			$data['ciblog_post_id'] = $this->request->post['ciblog_post_id'];
		} elseif (!empty($cicomment_info)) {
			$data['ciblog_post_id'] = $cicomment_info['ciblog_post_id'];
		} else {
			$data['ciblog_post_id'] = '';
		}

		if (isset($this->request->post['ciblog_post'])) {
			$data['ciblog_post'] = $this->request->post['ciblog_post'];
		} elseif (!empty($cicomment_info)) {
			$data['ciblog_post'] = $cicomment_info['ciblog_post'];
		} else {
			$data['ciblog_post'] = '';
		}

		if (isset($this->request->post['author'])) {
			$data['author'] = $this->request->post['author'];
		} elseif (!empty($cicomment_info)) {
			$data['author'] = $cicomment_info['author'];
		} else {
			$data['author'] = '';
		}

		if (isset($this->request->post['rating'])) {
			$data['rating'] = $this->request->post['rating'];
		} elseif (!empty($cicomment_info)) {
			$data['rating'] = $cicomment_info['rating'];
		} else {
			$data['rating'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($cicomment_info)) {
			$data['email'] = $cicomment_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['text'])) {
			$data['text'] = $this->request->post['text'];
		} elseif (!empty($cicomment_info)) {
			$data['text'] = $cicomment_info['text'];
		} else {
			$data['text'] = '';
		}

		if (isset($this->request->post['language_id'])) {
			$data['language_id'] = $this->request->post['language_id'];
		} elseif (!empty($cicomment_info)) {
			$data['language_id'] = $cicomment_info['language_id'];
		} else {
			$data['language_id'] = 0;
		}

		if (isset($this->request->post['store_id'])) {
			$data['store_id'] = $this->request->post['store_id'];
		} elseif (!empty($cicomment_info)) {
			$data['store_id'] = $cicomment_info['store_id'];
		} else {
			$data['store_id'] = 0;
		}

		if (isset($this->request->post['date_added'])) {
			$data['date_added'] = $this->request->post['date_added'];
		} elseif (!empty($cicomment_info)) {
			$data['date_added'] = ($cicomment_info['date_added'] != '0000-00-00 00:00:00' ? $cicomment_info['date_added'] : '');
		} else {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if (isset($this->request->post['date_modified'])) {
			$data['date_modified'] = $this->request->post['date_modified'];
		} elseif (!empty($cicomment_info)) {
			$data['date_modified'] = ($cicomment_info['date_modified'] != '0000-00-00 00:00:00' ? $cicomment_info['date_modified'] : '');
		} else {
			$data['date_modified'] = date('Y-m-d H:i:s');
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($cicomment_info)) {
			$data['status'] = $cicomment_info['status'];
		} else {
			$data['status'] = 1;
		}

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/cicomment_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/ciblog/cicomment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['ciblog_post_id']) {
			$this->error['ciblog_post'] = $this->language->get('error_ciblog_post');
		}

		// if ((utf8_strlen($this->request->post['title']) < 3) || (utf8_strlen($this->request->post['title']) > 255)) {
		// 	$this->error['title'] = $this->language->get('error_title');
		// }

		if (!isset($this->request->post['rating'])) {
			//$this->error['rating'] = $this->language->get('error_rating');
		} else {
			if($this->request->post['rating'] <= 0 || $this->request->post['rating'] > 5) {
				$this->error['rating'] = $this->language->get('error_rating');
			}
		}

		if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (utf8_strlen($this->request->post['text']) < 10) {
			$this->error['text'] = $this->language->get('error_text');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/ciblog/cicomment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}