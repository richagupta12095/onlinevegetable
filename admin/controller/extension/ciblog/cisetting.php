<?php
class ControllerExtensionCiBlogCiSetting extends Controller {
	private $error = array();

	public function __construct($registry) {
		parent :: __construct($registry);
		$this->ciblog->buildTable();
	}

	public function index() {
		$this->load->language('extension/ciblog/cisetting');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

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
			'href' => $this->url->link('extension/ciblog/cisetting', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
		);

		$data['add'] = $this->url->link('extension/ciblog/cisetting/add', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true);
		$data['delete'] = $this->url->link('extension/ciblog/cisetting/delete', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true);

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('extension/ciblog/cisetting/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken]. '&store_id=0' , true)
		);

		$this->load->model('setting/store');

		$store_total = $this->model_setting_store->getTotalStores();
		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'edit'     => $this->url->link('extension/ciblog/cisetting/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&store_id=' . $result['store_id'], true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_url'] = $this->language->get('column_url');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_edit'] = $this->language->get('button_edit');

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

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/cisetting_list', $data));
	}


	public function edit() {
		$this->load->language('extension/ciblog/cisetting');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			if(VERSION <= '2.3.0.2') {
				$query_path = 'extension/ciblog/ciblog-'.$this->request->get['store_id'];

				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '". $this->db->escape($query_path) ."'");

				if (utf8_strlen($this->request->post['ciblog_store_page_keyword']) > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '". $this->db->escape($query_path) ."', keyword = '" . $this->db->escape($this->request->post['ciblog_store_page_keyword']) . "'");
				}

			} else {
				$query_path = 'extension/ciblog/ciblog';
				foreach ($this->request->post['ciblog_store_page_keyword'] as $store_id => $language) {

					foreach ($language as $language_id => $keyword) {

						$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = '". $this->db->escape($query_path) ."' AND language_id='". (int)$language_id  ."' AND store_id='". (int)$this->request->get['store_id'] ."'");

						if (utf8_strlen($keyword) > 0) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$this->request->get['store_id'] . "', language_id = '" . (int)$language_id . "', query = '". $this->db->escape($query_path) ."', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}

			$this->model_setting_setting->editSetting('ciblog_store', $this->request->post, $this->request->get['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/ciblog/cisetting', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&store_id=' . $this->request->get['store_id'], true));
		}


		if (!isset($this->request->get['store_id'])) {
			$this->response->redirect($this->url->link('extension/ciblog/cisetting', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_w'] = $this->language->get('text_w');
		$data['text_h'] = $this->language->get('text_h');
		$data['text_only_customer'] = $this->language->get('text_only_customer');
		$data['text_guest_customer'] = $this->language->get('text_guest_customer');
		$data['text_no_one'] = $this->language->get('text_no_one');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_switch_buttons'] = $this->language->get('text_switch_buttons');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_can_like'] = $this->language->get('entry_can_like');
		$data['entry_rating_show'] = $this->language->get('entry_rating_show');
		$data['entry_post_count'] = $this->language->get('entry_post_count');
		$data['entry_blog_title'] = $this->language->get('entry_blog_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_keyword'] = $this->language->get('entry_keyword');

		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_blog_limit'] = $this->language->get('entry_blog_limit');
		$data['entry_blog_description_length'] = $this->language->get('entry_blog_description_length');
		$data['entry_blog_row'] = $this->language->get('entry_blog_row');
		$data['entry_blog_show_title'] = $this->language->get('entry_blog_show_title');
		$data['entry_blog_show_description'] = $this->language->get('entry_blog_show_description');
		$data['entry_blog_image_show_listing'] = $this->language->get('entry_blog_image_show_listing');
		$data['entry_blog_image_show_thumb'] = $this->language->get('entry_blog_image_show_thumb');
		$data['entry_blog_image_show_additional'] = $this->language->get('entry_blog_image_show_additional');
		$data['entry_blog_image_show_related'] = $this->language->get('entry_blog_image_show_related');
		$data['entry_blog_show_date_publish'] = $this->language->get('entry_blog_show_date_publish');
		$data['entry_blog_show_total_view'] = $this->language->get('entry_blog_show_total_view');
		$data['entry_blog_show_author'] = $this->language->get('entry_blog_show_author');
		$data['entry_blog_image_listing'] = $this->language->get('entry_blog_image_listing');
		$data['entry_blog_image_thumb'] = $this->language->get('entry_blog_image_thumb');
		$data['entry_blog_image_popup'] = $this->language->get('entry_blog_image_popup');
		$data['entry_blog_image_additional'] = $this->language->get('entry_blog_image_additional');
		$data['entry_blog_image_related'] = $this->language->get('entry_blog_image_related');

		$data['entry_blog_show_social_share'] = $this->language->get('entry_blog_show_social_share');

		$data['entry_blog_like_show_total'] = $this->language->get('entry_blog_like_show_total');

		$data['entry_blog_like_allow'] = $this->language->get('entry_blog_like_allow');
		$data['entry_blog_comment_limit'] = $this->language->get('entry_blog_comment_limit');
		$data['entry_blog_comment_captcha'] = $this->language->get('entry_blog_comment_captcha');
		$data['entry_blog_comment_show_total'] = $this->language->get('entry_blog_comment_show_total');
		$data['entry_blog_comment_allow'] = $this->language->get('entry_blog_comment_allow');
		$data['entry_blog_comment_allow_guest'] = $this->language->get('entry_blog_comment_allow_guest');
		$data['entry_blog_comment_show'] = $this->language->get('entry_blog_comment_show');
		$data['entry_blog_comment_snippet'] = $this->language->get('entry_blog_comment_snippet');
		$data['entry_blog_comment_approve'] = $this->language->get('entry_blog_comment_approve');
		$data['entry_blog_comment_alert'] = $this->language->get('entry_blog_comment_alert');


		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_blog_limit'] = $this->language->get('help_blog_limit');
		$data['help_blog_comment_limit'] = $this->language->get('help_blog_comment_limit');
		$data['help_post_count'] = $this->language->get('help_post_count');

		if(!$this->config->get('config_captcha')) {
			$data['error_config_captcha'] = $this->language->get('error_config_captcha');
		} else {
			$data['error_config_captcha'] = '';
		}

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_page'] = $this->language->get('tab_page');
		$data['tab_blog_listing'] = $this->language->get('tab_blog_listing');
		$data['tab_blog_page'] = $this->language->get('tab_blog_page');
		$data['tab_blog_related'] = $this->language->get('tab_blog_related');
		$data['tab_blog_category'] = $this->language->get('tab_blog_category');
		$data['tab_blog_search'] = $this->language->get('tab_blog_search');
		$data['tab_blog_author'] = $this->language->get('tab_blog_author');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if(VERSION <= '2.3.0.2') {
			if (isset($this->error['page_keyword'])) {
				$data['error_page_keyword'] = $this->error['page_keyword'];
			} else {
				$data['error_page_keyword'] = '';
			}

		} else {
			if (isset($this->error['page_keyword'])) {
				$data['error_page_keyword'] = $this->error['page_keyword'];
			} else {
				$data['error_page_keyword'] = array();
			}
		}
		if (isset($this->error['page_description'])) {
			$data['error_page_description'] = $this->error['page_description'];
		} else {
			$data['error_page_description'] = array();
		}

		if (isset($this->error['blog_limit'])) {
			$data['error_blog_limit'] = $this->error['blog_limit'];
		} else {
			$data['error_blog_limit'] = '';
		}
		if (isset($this->error['blogcategory_limit'])) {
			$data['error_blogcategory_limit'] = $this->error['blogcategory_limit'];
		} else {
			$data['error_blogcategory_limit'] = '';
		}
		if (isset($this->error['blogsearch_limit'])) {
			$data['error_blogsearch_limit'] = $this->error['blogsearch_limit'];
		} else {
			$data['error_blogsearch_limit'] = '';
		}
		if (isset($this->error['blogauthor_limit'])) {
			$data['error_blogauthor_limit'] = $this->error['blogauthor_limit'];
		} else {
			$data['error_blogauthor_limit'] = '';
		}

		if (isset($this->error['blogpage_comment_limit'])) {
			$data['error_blogpage_comment_limit'] = $this->error['blogpage_comment_limit'];
		} else {
			$data['error_blogpage_comment_limit'] = '';
		}

		/*characer limit*/
		if (isset($this->error['blog_description_length'])) {
			$data['error_blog_description_length'] = $this->error['blog_description_length'];
		} else {
			$data['error_blog_description_length'] = '';
		}
		if (isset($this->error['blogrelated_description_length'])) {
			$data['error_blogrelated_description_length'] = $this->error['blogrelated_description_length'];
		} else {
			$data['error_blogrelated_description_length'] = '';
		}
		if (isset($this->error['blogcategory_description_length'])) {
			$data['error_blogcategory_description_length'] = $this->error['blogcategory_description_length'];
		} else {
			$data['error_blogcategory_description_length'] = '';
		}
		if (isset($this->error['blogsearch_description_length'])) {
			$data['error_blogsearch_description_length'] = $this->error['blogsearch_description_length'];
		} else {
			$data['error_blogsearch_description_length'] = '';
		}
		if (isset($this->error['blogauthor_description_length'])) {
			$data['error_blogauthor_description_length'] = $this->error['blogauthor_description_length'];
		} else {
			$data['error_blogauthor_description_length'] = '';
		}
		/*characer limit*/

		if (isset($this->error['blog_row'])) {
			$data['error_blog_row'] = $this->error['blog_row'];
		} else {
			$data['error_blog_row'] = '';
		}

		if (isset($this->error['blogrelated_row'])) {
			$data['error_blogrelated_row'] = $this->error['blogrelated_row'];
		} else {
			$data['error_blogrelated_row'] = '';
		}

		if (isset($this->error['blogcategory_row'])) {
			$data['error_blogcategory_row'] = $this->error['blogcategory_row'];
		} else {
			$data['error_blogcategory_row'] = '';
		}

		if (isset($this->error['blogsearch_row'])) {
			$data['error_blogsearch_row'] = $this->error['blogsearch_row'];
		} else {
			$data['error_blogsearch_row'] = '';
		}
		if (isset($this->error['blogauthor_row'])) {
			$data['error_blogauthor_row'] = $this->error['blogauthor_row'];
		} else {
			$data['error_blogauthor_row'] = '';
		}

		if (isset($this->error['blog_image_listing'])) {
			$data['error_blog_image_listing'] = $this->error['blog_image_listing'];
		} else {
			$data['error_blog_image_listing'] = '';
		}

		if (isset($this->error['blogrelated_image_listing'])) {
			$data['error_blogrelated_image_listing'] = $this->error['blogrelated_image_listing'];
		} else {
			$data['error_blogrelated_image_listing'] = '';
		}

		if (isset($this->error['blogcategory_image_listing'])) {
			$data['error_blogcategory_image_listing'] = $this->error['blogcategory_image_listing'];
		} else {
			$data['error_blogcategory_image_listing'] = '';
		}

		if (isset($this->error['blogsearch_image_listing'])) {
			$data['error_blogsearch_image_listing'] = $this->error['blogsearch_image_listing'];
		} else {
			$data['error_blogsearch_image_listing'] = '';
		}
		if (isset($this->error['blogauthor_image_listing'])) {
			$data['error_blogauthor_image_listing'] = $this->error['blogauthor_image_listing'];
		} else {
			$data['error_blogauthor_image_listing'] = '';
		}

		if (isset($this->error['blog_image_thumb'])) {
			$data['error_blog_image_thumb'] = $this->error['blog_image_thumb'];
		} else {
			$data['error_blog_image_thumb'] = '';
		}
		if (isset($this->error['blog_image_popup'])) {
			$data['error_blog_image_popup'] = $this->error['blog_image_popup'];
		} else {
			$data['error_blog_image_popup'] = '';
		}
		if (isset($this->error['blog_image_additional'])) {
			$data['error_blog_image_additional'] = $this->error['blog_image_additional'];
		} else {
			$data['error_blog_image_additional'] = '';
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciblog/cisetting', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_settings'),
			'href' => $this->url->link('extension/ciblog/cisetting/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&store_id=' . $this->request->get['store_id'], true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('extension/ciblog/cisetting/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken] . '&store_id=' . $this->request->get['store_id'], true);

		$data['cancel'] = $this->url->link('extension/ciblog/cisetting', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true);

		$data['store_id'] = $this->request->get['store_id'];

		$data['stores'] = array();
		$data['stores'][] = array(
			'name' => $this->language->get('text_default'),
			'store_id' => '0'
		);
		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$data['stores'][] = array(
				'name' => $store['name'],
				'store_id' => $store['store_id']
			);
		}

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['languages'] = $this->ciblog->languages($data['languages']);

		/*make layouts if not exits*/
		$this->load->config('ciblog');
		$config_layouts = json_decode($this->config->get('ciblog_config_layouts'), 1);
		$this->load->model('design/layout');
		$layouts = $this->model_design_layout->getLayouts();

		$existing_layouts = array();

		foreach ($layouts as $layout) {
			// print_r($layout);
			$layout_routes = $this->model_design_layout->getLayoutRoutes($layout['layout_id']);

			foreach ($layout_routes as $layout_route) {
				$existing_layouts[$layout_route['route']][] = array(
					'store_id' => $layout_route['store_id'],
					'layout_id' => $layout_route['layout_id'],
					'name' => $layout['name'],
				);
			}
		}
		foreach ($config_layouts as $config_layout) {
			if(!isset($existing_layouts[$config_layout['route']])) {
				$layout_data = array();
				$layout_data['name'] = $config_layout['name'];
				$layout_data['layout_route'] = array();
				foreach ($data['stores'] as $store) {
					$layout_data['layout_route'][] = array(
						'store_id' => $store['store_id'],
						'route' => $config_layout['route'],
					);
				}

				if(!empty($layout_data['layout_route'])) {
					$layout_id = $this->model_design_layout->addLayout($layout_data);
					// add new created layout in existing layouts array.
					$layout_routes = $this->model_design_layout->getLayoutRoutes($layout_id);

					foreach ($layout_routes as $layout_route) {
						$existing_layouts[$layout_route['route']][] = array(
							'store_id' => $layout_route['store_id'],
							'layout_id' => $layout_route['layout_id'],
							'name' => $config_layout['name'],
						);
					}
				}
			}
		}


		if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$this->load->model('setting/setting');

			$store_info = $this->model_setting_setting->getSetting('ciblog_store', $this->request->get['store_id']);
		}

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		if (isset($this->request->post['ciblog_store_status'])) {
			$data['ciblog_store_status'] = $this->request->post['ciblog_store_status'];
		} elseif (isset($store_info['ciblog_store_status'])) {
			$data['ciblog_store_status'] = $store_info['ciblog_store_status'];
		} else {
			$data['ciblog_store_status'] = 0;
		}
		if (isset($this->request->post['ciblog_store_can_like'])) {
			$data['ciblog_store_can_like'] = $this->request->post['ciblog_store_can_like'];
		} elseif (isset($store_info['ciblog_store_can_like'])) {
			$data['ciblog_store_can_like'] = $store_info['ciblog_store_can_like'];
		} else {
			$data['ciblog_store_can_like'] = 'NOONE';
		}
		if (isset($this->request->post['ciblog_store_rating_show'])) {
			$data['ciblog_store_rating_show'] = $this->request->post['ciblog_store_rating_show'];
		} elseif (isset($store_info['ciblog_store_rating_show'])) {
			$data['ciblog_store_rating_show'] = $store_info['ciblog_store_rating_show'];
		} else {
			$data['ciblog_store_rating_show'] = 0;
		}
		if (isset($this->request->post['ciblog_store_post_count'])) {
			$data['ciblog_store_post_count'] = $this->request->post['ciblog_store_post_count'];
		} elseif (isset($store_info['ciblog_store_post_count'])) {
			$data['ciblog_store_post_count'] = $store_info['ciblog_store_post_count'];
		} else {
			$data['ciblog_store_post_count'] = 0;
		}

		if(VERSION <= '2.3.0.2') {
			if (isset($this->request->post['ciblog_store_page_keyword'])) {
			$data['ciblog_store_page_keyword'] = $this->request->post['ciblog_store_page_keyword'];
			} elseif (isset($store_info['ciblog_store_page_keyword'])) {
				$data['ciblog_store_page_keyword'] = $store_info['ciblog_store_page_keyword'];
				$queryy = 'extension/ciblog/ciblog-'.$this->request->get['store_id'];
				$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."url_alias WHERE `query` = '". $queryy ."'");
				if(!empty($query->row)) {
					$data['ciblog_store_page_keyword'] = $query->row['keyword'];
				}
			} else {
				$data['ciblog_store_page_keyword'] = '';
			}
		} else {
			if (isset($this->request->post['ciblog_store_page_keyword'])) {
				$data['ciblog_store_page_keyword'] = $this->request->post['ciblog_store_page_keyword'];
			} elseif (isset($store_info['ciblog_store_page_keyword'])) {
				$data['ciblog_store_page_keyword'] = (array)$store_info['ciblog_store_page_keyword'];

				$queryy = 'extension/ciblog/ciblog';
				$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."seo_url WHERE `query` = '". $queryy ."'");

				if($query->num_rows) {
					foreach ($query->rows as $row) {
						$data['ciblog_store_page_keyword'][$row['store_id']][$row['language_id']] = $row['keyword'];
					}
				}
			} else {
				$data['ciblog_store_page_keyword'] = array();
			}
		}

		if (isset($this->request->post['ciblog_store_page_description'])) {
			$data['ciblog_store_page_description'] = $this->request->post['ciblog_store_page_description'];
		} elseif (isset($store_info['ciblog_store_page_description'])) {
			$data['ciblog_store_page_description'] = (array)$store_info['ciblog_store_page_description'];
		} else {
			$data['ciblog_store_page_description'] = array();
		}

		if (isset($this->request->post['ciblog_store_blog_limit'])) {
			$data['ciblog_store_blog_limit'] = $this->request->post['ciblog_store_blog_limit'];
		} elseif (isset($store_info['ciblog_store_blog_limit'])) {
			$data['ciblog_store_blog_limit'] = $store_info['ciblog_store_blog_limit'];
		} else {
			$data['ciblog_store_blog_limit'] = 10;
		}
		if (isset($this->request->post['ciblog_store_blogcategory_limit'])) {
			$data['ciblog_store_blogcategory_limit'] = $this->request->post['ciblog_store_blogcategory_limit'];
		} elseif (isset($store_info['ciblog_store_blogcategory_limit'])) {
			$data['ciblog_store_blogcategory_limit'] = $store_info['ciblog_store_blogcategory_limit'];
		} else {
			$data['ciblog_store_blogcategory_limit'] = 10;
		}
		if (isset($this->request->post['ciblog_store_blogsearch_limit'])) {
			$data['ciblog_store_blogsearch_limit'] = $this->request->post['ciblog_store_blogsearch_limit'];
		} elseif (isset($store_info['ciblog_store_blogsearch_limit'])) {
			$data['ciblog_store_blogsearch_limit'] = $store_info['ciblog_store_blogsearch_limit'];
		} else {
			$data['ciblog_store_blogsearch_limit'] = 10;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_limit'])) {
			$data['ciblog_store_blogauthor_limit'] = $this->request->post['ciblog_store_blogauthor_limit'];
		} elseif (isset($store_info['ciblog_store_blogauthor_limit'])) {
			$data['ciblog_store_blogauthor_limit'] = $store_info['ciblog_store_blogauthor_limit'];
		} else {
			$data['ciblog_store_blogauthor_limit'] = 10;
		}

		if (isset($this->request->post['ciblog_store_blog_description_length'])) {
			$data['ciblog_store_blog_description_length'] = $this->request->post['ciblog_store_blog_description_length'];
		} elseif (isset($store_info['ciblog_store_blog_description_length'])) {
			$data['ciblog_store_blog_description_length'] = $store_info['ciblog_store_blog_description_length'];
		} else {
			$data['ciblog_store_blog_description_length'] = 200;
		}
		if (isset($this->request->post['ciblog_store_blogrelated_description_length'])) {
			$data['ciblog_store_blogrelated_description_length'] = $this->request->post['ciblog_store_blogrelated_description_length'];
		} elseif (isset($store_info['ciblog_store_blogrelated_description_length'])) {
			$data['ciblog_store_blogrelated_description_length'] = $store_info['ciblog_store_blogrelated_description_length'];
		} else {
			$data['ciblog_store_blogrelated_description_length'] = 200;
		}
		if (isset($this->request->post['ciblog_store_blogcategory_description_length'])) {
			$data['ciblog_store_blogcategory_description_length'] = $this->request->post['ciblog_store_blogcategory_description_length'];
		} elseif (isset($store_info['ciblog_store_blogcategory_description_length'])) {
			$data['ciblog_store_blogcategory_description_length'] = $store_info['ciblog_store_blogcategory_description_length'];
		} else {
			$data['ciblog_store_blogcategory_description_length'] = 200;
		}
		if (isset($this->request->post['ciblog_store_blogsearch_description_length'])) {
			$data['ciblog_store_blogsearch_description_length'] = $this->request->post['ciblog_store_blogsearch_description_length'];
		} elseif (isset($store_info['ciblog_store_blogsearch_description_length'])) {
			$data['ciblog_store_blogsearch_description_length'] = $store_info['ciblog_store_blogsearch_description_length'];
		} else {
			$data['ciblog_store_blogsearch_description_length'] = 200;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_description_length'])) {
			$data['ciblog_store_blogauthor_description_length'] = $this->request->post['ciblog_store_blogauthor_description_length'];
		} elseif (isset($store_info['ciblog_store_blogauthor_description_length'])) {
			$data['ciblog_store_blogauthor_description_length'] = $store_info['ciblog_store_blogauthor_description_length'];
		} else {
			$data['ciblog_store_blogauthor_description_length'] = 200;
		}

		if (isset($this->request->post['ciblog_store_blog_row'])) {
			$data['ciblog_store_blog_row'] = $this->request->post['ciblog_store_blog_row'];
		} elseif (isset($store_info['ciblog_store_blog_row'])) {
			$data['ciblog_store_blog_row'] = $store_info['ciblog_store_blog_row'];
		} else {
			$data['ciblog_store_blog_row'] = 2;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_row'])) {
			$data['ciblog_store_blogrelated_row'] = $this->request->post['ciblog_store_blogrelated_row'];
		} elseif (isset($store_info['ciblog_store_blogrelated_row'])) {
			$data['ciblog_store_blogrelated_row'] = $store_info['ciblog_store_blogrelated_row'];
		} else {
			$data['ciblog_store_blogrelated_row'] = 2;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_row'])) {
			$data['ciblog_store_blogcategory_row'] = $this->request->post['ciblog_store_blogcategory_row'];
		} elseif (isset($store_info['ciblog_store_blogcategory_row'])) {
			$data['ciblog_store_blogcategory_row'] = $store_info['ciblog_store_blogcategory_row'];
		} else {
			$data['ciblog_store_blogcategory_row'] = 2;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_row'])) {
			$data['ciblog_store_blogsearch_row'] = $this->request->post['ciblog_store_blogsearch_row'];
		} elseif (isset($store_info['ciblog_store_blogsearch_row'])) {
			$data['ciblog_store_blogsearch_row'] = $store_info['ciblog_store_blogsearch_row'];
		} else {
			$data['ciblog_store_blogsearch_row'] = 2;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_row'])) {
			$data['ciblog_store_blogauthor_row'] = $this->request->post['ciblog_store_blogauthor_row'];
		} elseif (isset($store_info['ciblog_store_blogauthor_row'])) {
			$data['ciblog_store_blogauthor_row'] = $store_info['ciblog_store_blogauthor_row'];
		} else {
			$data['ciblog_store_blogauthor_row'] = 2;
		}

		if (isset($this->request->post['ciblog_store_blog_show_title'])) {
			$data['ciblog_store_blog_show_title'] = $this->request->post['ciblog_store_blog_show_title'];
		} elseif (isset($store_info['ciblog_store_blog_show_title'])) {
			$data['ciblog_store_blog_show_title'] = $store_info['ciblog_store_blog_show_title'];
		} else {
			$data['ciblog_store_blog_show_title'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_show_title'])) {
			$data['ciblog_store_blogrelated_show_title'] = $this->request->post['ciblog_store_blogrelated_show_title'];
		} elseif (isset($store_info['ciblog_store_blogrelated_show_title'])) {
			$data['ciblog_store_blogrelated_show_title'] = $store_info['ciblog_store_blogrelated_show_title'];
		} else {
			$data['ciblog_store_blogrelated_show_title'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_show_title'])) {
			$data['ciblog_store_blogcategory_show_title'] = $this->request->post['ciblog_store_blogcategory_show_title'];
		} elseif (isset($store_info['ciblog_store_blogcategory_show_title'])) {
			$data['ciblog_store_blogcategory_show_title'] = $store_info['ciblog_store_blogcategory_show_title'];
		} else {
			$data['ciblog_store_blogcategory_show_title'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_show_title'])) {
			$data['ciblog_store_blogsearch_show_title'] = $this->request->post['ciblog_store_blogsearch_show_title'];
		} elseif (isset($store_info['ciblog_store_blogsearch_show_title'])) {
			$data['ciblog_store_blogsearch_show_title'] = $store_info['ciblog_store_blogsearch_show_title'];
		} else {
			$data['ciblog_store_blogsearch_show_title'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogauthor_show_title'])) {
			$data['ciblog_store_blogauthor_show_title'] = $this->request->post['ciblog_store_blogauthor_show_title'];
		} elseif (isset($store_info['ciblog_store_blogauthor_show_title'])) {
			$data['ciblog_store_blogauthor_show_title'] = $store_info['ciblog_store_blogauthor_show_title'];
		} else {
			$data['ciblog_store_blogauthor_show_title'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blog_show_description'])) {
			$data['ciblog_store_blog_show_description'] = $this->request->post['ciblog_store_blog_show_description'];
		} elseif (isset($store_info['ciblog_store_blog_show_description'])) {
			$data['ciblog_store_blog_show_description'] = $store_info['ciblog_store_blog_show_description'];
		} else {
			$data['ciblog_store_blog_show_description'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_show_description'])) {
			$data['ciblog_store_blogrelated_show_description'] = $this->request->post['ciblog_store_blogrelated_show_description'];
		} elseif (isset($store_info['ciblog_store_blogrelated_show_description'])) {
			$data['ciblog_store_blogrelated_show_description'] = $store_info['ciblog_store_blogrelated_show_description'];
		} else {
			$data['ciblog_store_blogrelated_show_description'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_show_description'])) {
			$data['ciblog_store_blogcategory_show_description'] = $this->request->post['ciblog_store_blogcategory_show_description'];
		} elseif (isset($store_info['ciblog_store_blogcategory_show_description'])) {
			$data['ciblog_store_blogcategory_show_description'] = $store_info['ciblog_store_blogcategory_show_description'];
		} else {
			$data['ciblog_store_blogcategory_show_description'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_show_description'])) {
			$data['ciblog_store_blogsearch_show_description'] = $this->request->post['ciblog_store_blogsearch_show_description'];
		} elseif (isset($store_info['ciblog_store_blogsearch_show_description'])) {
			$data['ciblog_store_blogsearch_show_description'] = $store_info['ciblog_store_blogsearch_show_description'];
		} else {
			$data['ciblog_store_blogsearch_show_description'] = 1;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_show_description'])) {
			$data['ciblog_store_blogauthor_show_description'] = $this->request->post['ciblog_store_blogauthor_show_description'];
		} elseif (isset($store_info['ciblog_store_blogauthor_show_description'])) {
			$data['ciblog_store_blogauthor_show_description'] = $store_info['ciblog_store_blogauthor_show_description'];
		} else {
			$data['ciblog_store_blogauthor_show_description'] = 1;
		}

		//blog image listing
		if (isset($this->request->post['ciblog_store_blog_image_listing_width'])) {
			$data['ciblog_store_blog_image_listing_width'] = $this->request->post['ciblog_store_blog_image_listing_width'];
		} elseif (isset($store_info['ciblog_store_blog_image_listing_width'])) {
			$data['ciblog_store_blog_image_listing_width'] = $store_info['ciblog_store_blog_image_listing_width'];
		} else {
			$data['ciblog_store_blog_image_listing_width'] = 438;
		}

		if (isset($this->request->post['ciblog_store_blog_image_listing_height'])) {
			$data['ciblog_store_blog_image_listing_height'] = $this->request->post['ciblog_store_blog_image_listing_height'];
		} elseif (isset($store_info['ciblog_store_blog_image_listing_height'])) {
			$data['ciblog_store_blog_image_listing_height'] = $store_info['ciblog_store_blog_image_listing_height'];
		} else {
			$data['ciblog_store_blog_image_listing_height'] = 292;
		}

		//blog related image listing
		if (isset($this->request->post['ciblog_store_blogrelated_image_listing_width'])) {
			$data['ciblog_store_blogrelated_image_listing_width'] = $this->request->post['ciblog_store_blogrelated_image_listing_width'];
		} elseif (isset($store_info['ciblog_store_blogrelated_image_listing_width'])) {
			$data['ciblog_store_blogrelated_image_listing_width'] = $store_info['ciblog_store_blogrelated_image_listing_width'];
		} else {
			$data['ciblog_store_blogrelated_image_listing_width'] = 438;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_image_listing_height'])) {
			$data['ciblog_store_blogrelated_image_listing_height'] = $this->request->post['ciblog_store_blogrelated_image_listing_height'];
		} elseif (isset($store_info['ciblog_store_blogrelated_image_listing_height'])) {
			$data['ciblog_store_blogrelated_image_listing_height'] = $store_info['ciblog_store_blogrelated_image_listing_height'];
		} else {
			$data['ciblog_store_blogrelated_image_listing_height'] = 292;
		}

		//blog category image listing
		if (isset($this->request->post['ciblog_store_blogcategory_image_listing_width'])) {
			$data['ciblog_store_blogcategory_image_listing_width'] = $this->request->post['ciblog_store_blogcategory_image_listing_width'];
		} elseif (isset($store_info['ciblog_store_blogcategory_image_listing_width'])) {
			$data['ciblog_store_blogcategory_image_listing_width'] = $store_info['ciblog_store_blogcategory_image_listing_width'];
		} else {
			$data['ciblog_store_blogcategory_image_listing_width'] = 438;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_image_listing_height'])) {
			$data['ciblog_store_blogcategory_image_listing_height'] = $this->request->post['ciblog_store_blogcategory_image_listing_height'];
		} elseif (isset($store_info['ciblog_store_blogcategory_image_listing_height'])) {
			$data['ciblog_store_blogcategory_image_listing_height'] = $store_info['ciblog_store_blogcategory_image_listing_height'];
		} else {
			$data['ciblog_store_blogcategory_image_listing_height'] = 292;
		}

		//blog search image listing
		if (isset($this->request->post['ciblog_store_blogsearch_image_listing_width'])) {
			$data['ciblog_store_blogsearch_image_listing_width'] = $this->request->post['ciblog_store_blogsearch_image_listing_width'];
		} elseif (isset($store_info['ciblog_store_blogsearch_image_listing_width'])) {
			$data['ciblog_store_blogsearch_image_listing_width'] = $store_info['ciblog_store_blogsearch_image_listing_width'];
		} else {
			$data['ciblog_store_blogsearch_image_listing_width'] = 438;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_image_listing_height'])) {
			$data['ciblog_store_blogsearch_image_listing_height'] = $this->request->post['ciblog_store_blogsearch_image_listing_height'];
		} elseif (isset($store_info['ciblog_store_blogsearch_image_listing_height'])) {
			$data['ciblog_store_blogsearch_image_listing_height'] = $store_info['ciblog_store_blogsearch_image_listing_height'];
		} else {
			$data['ciblog_store_blogsearch_image_listing_height'] = 292;
		}
		//blog author image listing
		if (isset($this->request->post['ciblog_store_blogauthor_image_listing_width'])) {
			$data['ciblog_store_blogauthor_image_listing_width'] = $this->request->post['ciblog_store_blogauthor_image_listing_width'];
		} elseif (isset($store_info['ciblog_store_blogauthor_image_listing_width'])) {
			$data['ciblog_store_blogauthor_image_listing_width'] = $store_info['ciblog_store_blogauthor_image_listing_width'];
		} else {
			$data['ciblog_store_blogauthor_image_listing_width'] = 438;
		}

		if (isset($this->request->post['ciblog_store_blogauthor_image_listing_height'])) {
			$data['ciblog_store_blogauthor_image_listing_height'] = $this->request->post['ciblog_store_blogauthor_image_listing_height'];
		} elseif (isset($store_info['ciblog_store_blogauthor_image_listing_height'])) {
			$data['ciblog_store_blogauthor_image_listing_height'] = $store_info['ciblog_store_blogauthor_image_listing_height'];
		} else {
			$data['ciblog_store_blogauthor_image_listing_height'] = 292;
		}

		//blog image thumb
		if (isset($this->request->post['ciblog_store_blog_image_thumb_width'])) {
			$data['ciblog_store_blog_image_thumb_width'] = $this->request->post['ciblog_store_blog_image_thumb_width'];
		} elseif (isset($store_info['ciblog_store_blog_image_thumb_width'])) {
			$data['ciblog_store_blog_image_thumb_width'] = $store_info['ciblog_store_blog_image_thumb_width'];
		} else {
			$data['ciblog_store_blog_image_thumb_width'] = 1024;
		}

		if (isset($this->request->post['ciblog_store_blog_image_thumb_height'])) {
			$data['ciblog_store_blog_image_thumb_height'] = $this->request->post['ciblog_store_blog_image_thumb_height'];
		} elseif (isset($store_info['ciblog_store_blog_image_thumb_height'])) {
			$data['ciblog_store_blog_image_thumb_height'] = $store_info['ciblog_store_blog_image_thumb_height'];
		} else {
			$data['ciblog_store_blog_image_thumb_height'] = 683;
		}

		//blog image popup
		if (isset($this->request->post['ciblog_store_blog_image_popup_width'])) {
			$data['ciblog_store_blog_image_popup_width'] = $this->request->post['ciblog_store_blog_image_popup_width'];
		} elseif (isset($store_info['ciblog_store_blog_image_popup_width'])) {
			$data['ciblog_store_blog_image_popup_width'] = $store_info['ciblog_store_blog_image_popup_width'];
		} else {
			$data['ciblog_store_blog_image_popup_width'] = 1024;
		}

		if (isset($this->request->post['ciblog_store_blog_image_popup_height'])) {
			$data['ciblog_store_blog_image_popup_height'] = $this->request->post['ciblog_store_blog_image_popup_height'];
		} elseif (isset($store_info['ciblog_store_blog_image_popup_height'])) {
			$data['ciblog_store_blog_image_popup_height'] = $store_info['ciblog_store_blog_image_popup_height'];
		} else {
			$data['ciblog_store_blog_image_popup_height'] = 683;
		}

		//blog image additional
		if (isset($this->request->post['ciblog_store_blog_image_additional_width'])) {
			$data['ciblog_store_blog_image_additional_width'] = $this->request->post['ciblog_store_blog_image_additional_width'];
		} elseif (isset($store_info['ciblog_store_blog_image_additional_width'])) {
			$data['ciblog_store_blog_image_additional_width'] = $store_info['ciblog_store_blog_image_additional_width'];
		} else {
			$data['ciblog_store_blog_image_additional_width'] = 424;
		}

		if (isset($this->request->post['ciblog_store_blog_image_additional_height'])) {
			$data['ciblog_store_blog_image_additional_height'] = $this->request->post['ciblog_store_blog_image_additional_height'];
		} elseif (isset($store_info['ciblog_store_blog_image_additional_height'])) {
			$data['ciblog_store_blog_image_additional_height'] = $store_info['ciblog_store_blog_image_additional_height'];
		} else {
			$data['ciblog_store_blog_image_additional_height'] = 283;
		}

		//blog image related
		if (isset($this->request->post['ciblog_store_blog_image_related_width'])) {
			$data['ciblog_store_blog_image_related_width'] = $this->request->post['ciblog_store_blog_image_related_width'];
		} elseif (isset($store_info['ciblog_store_blog_image_related_width'])) {
			$data['ciblog_store_blog_image_related_width'] = $store_info['ciblog_store_blog_image_related_width'];
		} else {
			$data['ciblog_store_blog_image_related_width'] = 80;
		}

		if (isset($this->request->post['ciblog_store_blog_image_related_height'])) {
			$data['ciblog_store_blog_image_related_height'] = $this->request->post['ciblog_store_blog_image_related_height'];
		} elseif (isset($store_info['ciblog_store_blog_image_related_height'])) {
			$data['ciblog_store_blog_image_related_height'] = $store_info['ciblog_store_blog_image_related_height'];
		} else {
			$data['ciblog_store_blog_image_related_height'] = 80;
		}

		if (isset($this->request->post['ciblog_store_blog_image_show_listing'])) {
			$data['ciblog_store_blog_image_show_listing'] = $this->request->post['ciblog_store_blog_image_show_listing'];
		} elseif (isset($store_info['ciblog_store_blog_image_show_listing'])) {
			$data['ciblog_store_blog_image_show_listing'] = $store_info['ciblog_store_blog_image_show_listing'];
		} else {
			$data['ciblog_store_blog_image_show_listing'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_image_show_listing'])) {
			$data['ciblog_store_blogrelated_image_show_listing'] = $this->request->post['ciblog_store_blogrelated_image_show_listing'];
		} elseif (isset($store_info['ciblog_store_blogrelated_image_show_listing'])) {
			$data['ciblog_store_blogrelated_image_show_listing'] = $store_info['ciblog_store_blogrelated_image_show_listing'];
		} else {
			$data['ciblog_store_blogrelated_image_show_listing'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_image_show_listing'])) {
			$data['ciblog_store_blogcategory_image_show_listing'] = $this->request->post['ciblog_store_blogcategory_image_show_listing'];
		} elseif (isset($store_info['ciblog_store_blogcategory_image_show_listing'])) {
			$data['ciblog_store_blogcategory_image_show_listing'] = $store_info['ciblog_store_blogcategory_image_show_listing'];
		} else {
			$data['ciblog_store_blogcategory_image_show_listing'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_image_show_listing'])) {
			$data['ciblog_store_blogsearch_image_show_listing'] = $this->request->post['ciblog_store_blogsearch_image_show_listing'];
		} elseif (isset($store_info['ciblog_store_blogsearch_image_show_listing'])) {
			$data['ciblog_store_blogsearch_image_show_listing'] = $store_info['ciblog_store_blogsearch_image_show_listing'];
		} else {
			$data['ciblog_store_blogsearch_image_show_listing'] = 1;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_image_show_listing'])) {
			$data['ciblog_store_blogauthor_image_show_listing'] = $this->request->post['ciblog_store_blogauthor_image_show_listing'];
		} elseif (isset($store_info['ciblog_store_blogauthor_image_show_listing'])) {
			$data['ciblog_store_blogauthor_image_show_listing'] = $store_info['ciblog_store_blogauthor_image_show_listing'];
		} else {
			$data['ciblog_store_blogauthor_image_show_listing'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blog_image_show_thumb'])) {
			$data['ciblog_store_blog_image_show_thumb'] = $this->request->post['ciblog_store_blog_image_show_thumb'];
		} elseif (isset($store_info['ciblog_store_blog_image_show_thumb'])) {
			$data['ciblog_store_blog_image_show_thumb'] = $store_info['ciblog_store_blog_image_show_thumb'];
		} else {
			$data['ciblog_store_blog_image_show_thumb'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blog_image_show_additional'])) {
			$data['ciblog_store_blog_image_show_additional'] = $this->request->post['ciblog_store_blog_image_show_additional'];
		} elseif (isset($store_info['ciblog_store_blog_image_show_additional'])) {
			$data['ciblog_store_blog_image_show_additional'] = $store_info['ciblog_store_blog_image_show_additional'];
		} else {
			$data['ciblog_store_blog_image_show_additional'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blog_image_show_related'])) {
			$data['ciblog_store_blog_image_show_related'] = $this->request->post['ciblog_store_blog_image_show_related'];
		} elseif (isset($store_info['ciblog_store_blog_image_show_related'])) {
			$data['ciblog_store_blog_image_show_related'] = $store_info['ciblog_store_blog_image_show_related'];
		} else {
			$data['ciblog_store_blog_image_show_related'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blog_show_date_publish'])) {
			$data['ciblog_store_blog_show_date_publish'] = $this->request->post['ciblog_store_blog_show_date_publish'];
		} elseif (isset($store_info['ciblog_store_blog_show_date_publish'])) {
			$data['ciblog_store_blog_show_date_publish'] = $store_info['ciblog_store_blog_show_date_publish'];
		} else {
			$data['ciblog_store_blog_show_date_publish'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_show_date_publish'])) {
			$data['ciblog_store_blogrelated_show_date_publish'] = $this->request->post['ciblog_store_blogrelated_show_date_publish'];
		} elseif (isset($store_info['ciblog_store_blogrelated_show_date_publish'])) {
			$data['ciblog_store_blogrelated_show_date_publish'] = $store_info['ciblog_store_blogrelated_show_date_publish'];
		} else {
			$data['ciblog_store_blogrelated_show_date_publish'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_show_date_publish'])) {
			$data['ciblog_store_blogcategory_show_date_publish'] = $this->request->post['ciblog_store_blogcategory_show_date_publish'];
		} elseif (isset($store_info['ciblog_store_blogcategory_show_date_publish'])) {
			$data['ciblog_store_blogcategory_show_date_publish'] = $store_info['ciblog_store_blogcategory_show_date_publish'];
		} else {
			$data['ciblog_store_blogcategory_show_date_publish'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_show_date_publish'])) {
			$data['ciblog_store_blogsearch_show_date_publish'] = $this->request->post['ciblog_store_blogsearch_show_date_publish'];
		} elseif (isset($store_info['ciblog_store_blogsearch_show_date_publish'])) {
			$data['ciblog_store_blogsearch_show_date_publish'] = $store_info['ciblog_store_blogsearch_show_date_publish'];
		} else {
			$data['ciblog_store_blogsearch_show_date_publish'] = 1;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_show_date_publish'])) {
			$data['ciblog_store_blogauthor_show_date_publish'] = $this->request->post['ciblog_store_blogauthor_show_date_publish'];
		} elseif (isset($store_info['ciblog_store_blogauthor_show_date_publish'])) {
			$data['ciblog_store_blogauthor_show_date_publish'] = $store_info['ciblog_store_blogauthor_show_date_publish'];
		} else {
			$data['ciblog_store_blogauthor_show_date_publish'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_show_date_publish'])) {
			$data['ciblog_store_blogpage_show_date_publish'] = $this->request->post['ciblog_store_blogpage_show_date_publish'];
		} elseif (isset($store_info['ciblog_store_blogpage_show_date_publish'])) {
			$data['ciblog_store_blogpage_show_date_publish'] = $store_info['ciblog_store_blogpage_show_date_publish'];
		} else {
			$data['ciblog_store_blogpage_show_date_publish'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blog_show_total_view'])) {
			$data['ciblog_store_blog_show_total_view'] = $this->request->post['ciblog_store_blog_show_total_view'];
		} elseif (isset($store_info['ciblog_store_blog_show_total_view'])) {
			$data['ciblog_store_blog_show_total_view'] = $store_info['ciblog_store_blog_show_total_view'];
		} else {
			$data['ciblog_store_blog_show_total_view'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_show_total_view'])) {
			$data['ciblog_store_blogrelated_show_total_view'] = $this->request->post['ciblog_store_blogrelated_show_total_view'];
		} elseif (isset($store_info['ciblog_store_blogrelated_show_total_view'])) {
			$data['ciblog_store_blogrelated_show_total_view'] = $store_info['ciblog_store_blogrelated_show_total_view'];
		} else {
			$data['ciblog_store_blogrelated_show_total_view'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_show_total_view'])) {
			$data['ciblog_store_blogcategory_show_total_view'] = $this->request->post['ciblog_store_blogcategory_show_total_view'];
		} elseif (isset($store_info['ciblog_store_blogcategory_show_total_view'])) {
			$data['ciblog_store_blogcategory_show_total_view'] = $store_info['ciblog_store_blogcategory_show_total_view'];
		} else {
			$data['ciblog_store_blogcategory_show_total_view'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_show_total_view'])) {
			$data['ciblog_store_blogsearch_show_total_view'] = $this->request->post['ciblog_store_blogsearch_show_total_view'];
		} elseif (isset($store_info['ciblog_store_blogsearch_show_total_view'])) {
			$data['ciblog_store_blogsearch_show_total_view'] = $store_info['ciblog_store_blogsearch_show_total_view'];
		} else {
			$data['ciblog_store_blogsearch_show_total_view'] = 1;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_show_total_view'])) {
			$data['ciblog_store_blogauthor_show_total_view'] = $this->request->post['ciblog_store_blogauthor_show_total_view'];
		} elseif (isset($store_info['ciblog_store_blogauthor_show_total_view'])) {
			$data['ciblog_store_blogauthor_show_total_view'] = $store_info['ciblog_store_blogauthor_show_total_view'];
		} else {
			$data['ciblog_store_blogauthor_show_total_view'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_show_total_view'])) {
			$data['ciblog_store_blogpage_show_total_view'] = $this->request->post['ciblog_store_blogpage_show_total_view'];
		} elseif (isset($store_info['ciblog_store_blogpage_show_total_view'])) {
			$data['ciblog_store_blogpage_show_total_view'] = $store_info['ciblog_store_blogpage_show_total_view'];
		} else {
			$data['ciblog_store_blogpage_show_total_view'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blog_show_author'])) {
			$data['ciblog_store_blog_show_author'] = $this->request->post['ciblog_store_blog_show_author'];
		} elseif (isset($store_info['ciblog_store_blog_show_author'])) {
			$data['ciblog_store_blog_show_author'] = $store_info['ciblog_store_blog_show_author'];
		} else {
			$data['ciblog_store_blog_show_author'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_show_author'])) {
			$data['ciblog_store_blogrelated_show_author'] = $this->request->post['ciblog_store_blogrelated_show_author'];
		} elseif (isset($store_info['ciblog_store_blogrelated_show_author'])) {
			$data['ciblog_store_blogrelated_show_author'] = $store_info['ciblog_store_blogrelated_show_author'];
		} else {
			$data['ciblog_store_blogrelated_show_author'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_show_author'])) {
			$data['ciblog_store_blogcategory_show_author'] = $this->request->post['ciblog_store_blogcategory_show_author'];
		} elseif (isset($store_info['ciblog_store_blogcategory_show_author'])) {
			$data['ciblog_store_blogcategory_show_author'] = $store_info['ciblog_store_blogcategory_show_author'];
		} else {
			$data['ciblog_store_blogcategory_show_author'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_show_author'])) {
			$data['ciblog_store_blogsearch_show_author'] = $this->request->post['ciblog_store_blogsearch_show_author'];
		} elseif (isset($store_info['ciblog_store_blogsearch_show_author'])) {
			$data['ciblog_store_blogsearch_show_author'] = $store_info['ciblog_store_blogsearch_show_author'];
		} else {
			$data['ciblog_store_blogsearch_show_author'] = 1;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_show_author'])) {
			$data['ciblog_store_blogauthor_show_author'] = $this->request->post['ciblog_store_blogauthor_show_author'];
		} elseif (isset($store_info['ciblog_store_blogauthor_show_author'])) {
			$data['ciblog_store_blogauthor_show_author'] = $store_info['ciblog_store_blogauthor_show_author'];
		} else {
			$data['ciblog_store_blogauthor_show_author'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_show_author'])) {
			$data['ciblog_store_blogpage_show_author'] = $this->request->post['ciblog_store_blogpage_show_author'];
		} elseif (isset($store_info['ciblog_store_blogpage_show_author'])) {
			$data['ciblog_store_blogpage_show_author'] = $store_info['ciblog_store_blogpage_show_author'];
		} else {
			$data['ciblog_store_blogpage_show_author'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blog_like_show_total'])) {
			$data['ciblog_store_blog_like_show_total'] = $this->request->post['ciblog_store_blog_like_show_total'];
		} elseif (isset($store_info['ciblog_store_blog_like_show_total'])) {
			$data['ciblog_store_blog_like_show_total'] = $store_info['ciblog_store_blog_like_show_total'];
		} else {
			$data['ciblog_store_blog_like_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_like_show_total'])) {
			$data['ciblog_store_blogrelated_like_show_total'] = $this->request->post['ciblog_store_blogrelated_like_show_total'];
		} elseif (isset($store_info['ciblog_store_blogrelated_like_show_total'])) {
			$data['ciblog_store_blogrelated_like_show_total'] = $store_info['ciblog_store_blogrelated_like_show_total'];
		} else {
			$data['ciblog_store_blogrelated_like_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_like_show_total'])) {
			$data['ciblog_store_blogcategory_like_show_total'] = $this->request->post['ciblog_store_blogcategory_like_show_total'];
		} elseif (isset($store_info['ciblog_store_blogcategory_like_show_total'])) {
			$data['ciblog_store_blogcategory_like_show_total'] = $store_info['ciblog_store_blogcategory_like_show_total'];
		} else {
			$data['ciblog_store_blogcategory_like_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_like_show_total'])) {
			$data['ciblog_store_blogsearch_like_show_total'] = $this->request->post['ciblog_store_blogsearch_like_show_total'];
		} elseif (isset($store_info['ciblog_store_blogsearch_like_show_total'])) {
			$data['ciblog_store_blogsearch_like_show_total'] = $store_info['ciblog_store_blogsearch_like_show_total'];
		} else {
			$data['ciblog_store_blogsearch_like_show_total'] = 1;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_like_show_total'])) {
			$data['ciblog_store_blogauthor_like_show_total'] = $this->request->post['ciblog_store_blogauthor_like_show_total'];
		} elseif (isset($store_info['ciblog_store_blogauthor_like_show_total'])) {
			$data['ciblog_store_blogauthor_like_show_total'] = $store_info['ciblog_store_blogauthor_like_show_total'];
		} else {
			$data['ciblog_store_blogauthor_like_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_like_show_total'])) {
			$data['ciblog_store_blogpage_like_show_total'] = $this->request->post['ciblog_store_blogpage_like_show_total'];
		} elseif (isset($store_info['ciblog_store_blogpage_like_show_total'])) {
			$data['ciblog_store_blogpage_like_show_total'] = $store_info['ciblog_store_blogpage_like_show_total'];
		} else {
			$data['ciblog_store_blogpage_like_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_like_allow'])) {
			$data['ciblog_store_blogpage_like_allow'] = $this->request->post['ciblog_store_blogpage_like_allow'];
		} elseif (isset($store_info['ciblog_store_blogpage_like_allow'])) {
			$data['ciblog_store_blogpage_like_allow'] = $store_info['ciblog_store_blogpage_like_allow'];
		} else {
			$data['ciblog_store_blogpage_like_allow'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_show_social_share'])) {
			$data['ciblog_store_blogpage_show_social_share'] = $this->request->post['ciblog_store_blogpage_show_social_share'];
		} elseif (isset($store_info['ciblog_store_blogpage_show_social_share'])) {
			$data['ciblog_store_blogpage_show_social_share'] = $store_info['ciblog_store_blogpage_show_social_share'];
		} else {
			$data['ciblog_store_blogpage_show_social_share'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_comment_allow'])) {
			$data['ciblog_store_blogpage_comment_allow'] = $this->request->post['ciblog_store_blogpage_comment_allow'];
		} elseif (isset($store_info['ciblog_store_blogpage_comment_allow'])) {
			$data['ciblog_store_blogpage_comment_allow'] = $store_info['ciblog_store_blogpage_comment_allow'];
		} else {
			$data['ciblog_store_blogpage_comment_allow'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_comment_allow_guest'])) {
			$data['ciblog_store_blogpage_comment_allow_guest'] = $this->request->post['ciblog_store_blogpage_comment_allow_guest'];
		} elseif (isset($store_info['ciblog_store_blogpage_comment_allow_guest'])) {
			$data['ciblog_store_blogpage_comment_allow_guest'] = $store_info['ciblog_store_blogpage_comment_allow_guest'];
		} else {
			$data['ciblog_store_blogpage_comment_allow_guest'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blog_comment_show_total'])) {
			$data['ciblog_store_blog_comment_show_total'] = $this->request->post['ciblog_store_blog_comment_show_total'];
		} elseif (isset($store_info['ciblog_store_blog_comment_show_total'])) {
			$data['ciblog_store_blog_comment_show_total'] = $store_info['ciblog_store_blog_comment_show_total'];
		} else {
			$data['ciblog_store_blog_comment_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogrelated_comment_show_total'])) {
			$data['ciblog_store_blogrelated_comment_show_total'] = $this->request->post['ciblog_store_blogrelated_comment_show_total'];
		} elseif (isset($store_info['ciblog_store_blogrelated_comment_show_total'])) {
			$data['ciblog_store_blogrelated_comment_show_total'] = $store_info['ciblog_store_blogrelated_comment_show_total'];
		} else {
			$data['ciblog_store_blogrelated_comment_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogcategory_comment_show_total'])) {
			$data['ciblog_store_blogcategory_comment_show_total'] = $this->request->post['ciblog_store_blogcategory_comment_show_total'];
		} elseif (isset($store_info['ciblog_store_blogcategory_comment_show_total'])) {
			$data['ciblog_store_blogcategory_comment_show_total'] = $store_info['ciblog_store_blogcategory_comment_show_total'];
		} else {
			$data['ciblog_store_blogcategory_comment_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogsearch_comment_show_total'])) {
			$data['ciblog_store_blogsearch_comment_show_total'] = $this->request->post['ciblog_store_blogsearch_comment_show_total'];
		} elseif (isset($store_info['ciblog_store_blogsearch_comment_show_total'])) {
			$data['ciblog_store_blogsearch_comment_show_total'] = $store_info['ciblog_store_blogsearch_comment_show_total'];
		} else {
			$data['ciblog_store_blogsearch_comment_show_total'] = 1;
		}
		if (isset($this->request->post['ciblog_store_blogauthor_comment_show_total'])) {
			$data['ciblog_store_blogauthor_comment_show_total'] = $this->request->post['ciblog_store_blogauthor_comment_show_total'];
		} elseif (isset($store_info['ciblog_store_blogauthor_comment_show_total'])) {
			$data['ciblog_store_blogauthor_comment_show_total'] = $store_info['ciblog_store_blogauthor_comment_show_total'];
		} else {
			$data['ciblog_store_blogauthor_comment_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_comment_show_total'])) {
			$data['ciblog_store_blogpage_comment_show_total'] = $this->request->post['ciblog_store_blogpage_comment_show_total'];
		} elseif (isset($store_info['ciblog_store_blogpage_comment_show_total'])) {
			$data['ciblog_store_blogpage_comment_show_total'] = $store_info['ciblog_store_blogpage_comment_show_total'];
		} else {
			$data['ciblog_store_blogpage_comment_show_total'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_comment_show'])) {
			$data['ciblog_store_blogpage_comment_show'] = $this->request->post['ciblog_store_blogpage_comment_show'];
		} elseif (isset($store_info['ciblog_store_blogpage_comment_show'])) {
			$data['ciblog_store_blogpage_comment_show'] = $store_info['ciblog_store_blogpage_comment_show'];
		} else {
			$data['ciblog_store_blogpage_comment_show'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_comment_snippet'])) {
			$data['ciblog_store_blogpage_comment_snippet'] = $this->request->post['ciblog_store_blogpage_comment_snippet'];
		} elseif (isset($store_info['ciblog_store_blogpage_comment_snippet'])) {
			$data['ciblog_store_blogpage_comment_snippet'] = $store_info['ciblog_store_blogpage_comment_snippet'];
		} else {
			$data['ciblog_store_blogpage_comment_snippet'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_comment_limit'])) {
			$data['ciblog_store_blogpage_comment_limit'] = $this->request->post['ciblog_store_blogpage_comment_limit'];
		} elseif (isset($store_info['ciblog_store_blogpage_comment_limit'])) {
			$data['ciblog_store_blogpage_comment_limit'] = $store_info['ciblog_store_blogpage_comment_limit'];
		} else {
			$data['ciblog_store_blogpage_comment_limit'] = 10;
		}

		if (isset($this->request->post['ciblog_store_blogpage_comment_captcha'])) {
			$data['ciblog_store_blogpage_comment_captcha'] = $this->request->post['ciblog_store_blogpage_comment_captcha'];
		} elseif (isset($store_info['ciblog_store_blogpage_comment_captcha'])) {
			$data['ciblog_store_blogpage_comment_captcha'] = $store_info['ciblog_store_blogpage_comment_captcha'];
		} else {
			$data['ciblog_store_blogpage_comment_captcha'] = 1;
		}

		if (isset($this->request->post['ciblog_store_blogpage_comment_approve'])) {
			$data['ciblog_store_blogpage_comment_approve'] = $this->request->post['ciblog_store_blogpage_comment_approve'];
		} elseif (isset($store_info['ciblog_store_blogpage_comment_approve'])) {
			$data['ciblog_store_blogpage_comment_approve'] = $store_info['ciblog_store_blogpage_comment_approve'];
		} else {
			$data['ciblog_store_blogpage_comment_approve'] = 'NO';
		}

		if (isset($this->request->post['ciblog_store_blogpage_comment_alert'])) {
			$data['ciblog_store_blogpage_comment_alert'] = $this->request->post['ciblog_store_blogpage_comment_alert'];
		} elseif (isset($store_info['ciblog_store_blogpage_comment_alert'])) {
			$data['ciblog_store_blogpage_comment_alert'] = $store_info['ciblog_store_blogpage_comment_alert'];
		} else {
			$data['ciblog_store_blogpage_comment_alert'] = 1;
		}

		$data['display_rows'] = array(1,2,3,4);


		$data['text_editor'] = $this->ciblog->getTexEditorFiles($data);

		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/cisetting_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/ciblog/cisetting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['ciblog_store_page_description'] as $language_id => $value) {

			if ((utf8_strlen($value['blog_title']) < 3) || (utf8_strlen($value['blog_title']) > 255)) {
				$this->error['page_description'][$language_id]['blog_title'] = $this->language->get('error_title');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['page_description'][$language_id]['meta_title'] = $this->language->get('error_meta_title');
			}
		}



		/*seo url validation starts*/
		if(VERSION <= '2.3.0.2') {
			if (utf8_strlen($this->request->post['ciblog_store_page_keyword']) > 0) {
				$this->load->model('catalog/url_alias');

				$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['ciblog_store_page_keyword']);

				$query_path = 'extension/ciblog/ciblog-'.$this->request->get['store_id'];

				if (
					($url_alias_info && $url_alias_info['keyword'] != $this->request->post['ciblog_store_page_keyword'])
					||($url_alias_info && $url_alias_info['query'] != $query_path)
				) {
					$this->error['page_keyword'] = $this->language->get('error_keyword');
				}
			}
		} else {
			$this->load->model('design/seo_url');
			$query_path = 'extension/ciblog/ciblog';

			foreach ($this->request->post['ciblog_store_page_keyword'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (utf8_strlen($keyword) > 0) {
						if (count(array_keys($language, $keyword)) > 1) {
							$this->error['page_keyword'][$store_id][$language_id] = $this->language->get('error_unique');
						}
						$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);

						foreach ($seo_urls as $seo_url) {
							if (($seo_url['store_id'] == $store_id) && ( $keyword != $seo_url['keyword'] || ($seo_url['query'] != $query_path))) {
								$this->error['page_keyword'][$store_id][$language_id] = $this->language->get('error_keyword');

								break;
							}
						}
					}
				}
			}
		}
		/*seo url validation ends*/


		if(!(int)$this->request->post['ciblog_store_blog_limit']) {
			$this->error['blog_limit'] = $this->language->get('error_blog_limit');
		}
		if(!(int)$this->request->post['ciblog_store_blog_row']) {
			$this->error['blog_row'] = $this->language->get('error_blog_row');
		}
		if(!(int)$this->request->post['ciblog_store_blog_description_length']) {
			$this->error['blog_description_length'] = $this->language->get('error_blog_description_length');
		}
		if(!(int)$this->request->post['ciblog_store_blog_image_listing_width'] || !(int)$this->request->post['ciblog_store_blog_image_listing_height']) {
			$this->error['blog_image_listing'] = $this->language->get('error_blog_image_listing');
		}

		if(!(int)$this->request->post['ciblog_store_blog_image_thumb_width'] || !(int)$this->request->post['ciblog_store_blog_image_thumb_height']) {
			$this->error['blog_image_thumb'] = $this->language->get('error_blog_image_thumb');
		}

		if(!(int)$this->request->post['ciblog_store_blog_image_popup_width'] || !(int)$this->request->post['ciblog_store_blog_image_popup_height']) {
			$this->error['blog_image_popup'] = $this->language->get('error_blog_image_popup');
		}

		if(!(int)$this->request->post['ciblog_store_blog_image_additional_width'] || !(int)$this->request->post['ciblog_store_blog_image_additional_height']) {
			$this->error['blog_image_additional'] = $this->language->get('error_blog_image_additional');
		}

		if(!(int)$this->request->post['ciblog_store_blogpage_comment_limit']) {
			$this->error['blogpage_comment_limit'] = $this->language->get('error_blogpage_comment_limit');
		}

		if(!(int)$this->request->post['ciblog_store_blogrelated_row']) {
			$this->error['blogrelated_row'] = $this->language->get('error_blog_row');
		}

		if(!(int)$this->request->post['ciblog_store_blogrelated_description_length']) {
			$this->error['blogrelated_description_length'] = $this->language->get('error_blog_description_length');
		}

		if(!(int)$this->request->post['ciblog_store_blogrelated_image_listing_width'] || !(int)$this->request->post['ciblog_store_blogrelated_image_listing_height']) {
			$this->error['blogrelated_image_listing'] = $this->language->get('error_blog_image_listing');
		}

		if(!(int)$this->request->post['ciblog_store_blogcategory_limit']) {
			$this->error['blogcategory_limit'] = $this->language->get('error_blog_limit');
		}

		if(!(int)$this->request->post['ciblog_store_blogcategory_row']) {
			$this->error['blogcategory_row'] = $this->language->get('error_blog_row');
		}

		if(!(int)$this->request->post['ciblog_store_blogcategory_description_length']) {
			$this->error['blogcategory_description_length'] = $this->language->get('error_blog_description_length');
		}

		if(!(int)$this->request->post['ciblog_store_blogcategory_image_listing_width'] || !(int)$this->request->post['ciblog_store_blogcategory_image_listing_height']) {
			$this->error['blogcategory_image_listing'] = $this->language->get('error_blog_image_listing');
		}


		if(!(int)$this->request->post['ciblog_store_blogsearch_limit']) {
			$this->error['blogsearch_limit'] = $this->language->get('error_blog_limit');
		}

		if(!(int)$this->request->post['ciblog_store_blogsearch_row']) {
			$this->error['blogsearch_row'] = $this->language->get('error_blog_row');
		}

		if(!(int)$this->request->post['ciblog_store_blogsearch_description_length']) {
			$this->error['blogsearch_description_length'] = $this->language->get('error_blog_description_length');
		}

		if(!(int)$this->request->post['ciblog_store_blogsearch_image_listing_width'] || !(int)$this->request->post['ciblog_store_blogsearch_image_listing_height']) {
			$this->error['blogsearch_image_listing'] = $this->language->get('error_blog_image_listing');
		}

		if(!(int)$this->request->post['ciblog_store_blogauthor_limit']) {
			$this->error['blogauthor_limit'] = $this->language->get('error_blog_limit');
		}

		if(!(int)$this->request->post['ciblog_store_blogauthor_row']) {
			$this->error['blogauthor_row'] = $this->language->get('error_blog_row');
		}

		if(!(int)$this->request->post['ciblog_store_blogauthor_description_length']) {
			$this->error['blogauthor_description_length'] = $this->language->get('error_blog_description_length');
		}

		if(!(int)$this->request->post['ciblog_store_blogauthor_image_listing_width'] || !(int)$this->request->post['ciblog_store_blogauthor_image_listing_height']) {
			$this->error['blogauthor_image_listing'] = $this->language->get('error_blog_image_listing');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}