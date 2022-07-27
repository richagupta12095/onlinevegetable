<?php
class ControllerExtensionCiBlogCiAuthor extends Controller {
	public function index() {
		$this->load->language('extension/ciblog/ciauthor');

		$this->load->model('extension/ciblog/ciauthor');

		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/theme/default/stylesheet/ciblog.css');
		$this->document->addScript('catalog/view/javascript/ciblog/ciblog.js');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_index'] = $this->language->get('text_index');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_brand'),
			'href' => $this->url->link('extension/ciblog/ciauthor')
		);

		$data['categories'] = array();

		$results = $this->model_extension_ciblog_ciauthor->getCiAuthors();

		foreach ($results as $result) {
			if (is_numeric(utf8_substr(htmlentities($result['name'], ENT_QUOTES, 'UTF-8'), 0, 1))) {
				$key = '0 - 9';
			} else {
				$key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
			}

			if (!isset($data['categories'][$key])) {
				$data['categories'][$key]['name'] = $key;
			}


			if(!empty($result['image']) && is_file(DIR_IMAGE . $result['image'])) {
				$result['thumb'] = $this->model_tool_image->resize($result['image'], 50, 50);
			} else {
				$result['thumb'] = $this->model_tool_image->resize('placeholder.png', 50, 50);
			}
			$result['oname'] = $result['name'];
			$result['name'] = htmlentities($result['name'], ENT_QUOTES, 'UTF-8');

			$data['categories'][$key]['author'][] = array(
				'name' => $result['name'],
				'thumb' => $result['thumb'],
				'image_alt' => $result['image_alt'] ? $result['image_alt'] : $result['name'],
				'image_title' => $result['image_title'] ? $result['image_title'] : $result['name'],
				'href' => $this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id=' . $result['ciblog_author_id'])
			);
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/ciauthor_list', $data));
	}

	public function info() {
		$this->load->language('extension/ciblog/ciauthor');
		$this->load->language('extension/ciblog/ciblog_common');

		$this->load->model('extension/ciblog/ciauthor');
		$this->load->model('extension/ciblog/ciblogpost');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/theme/default/stylesheet/ciblog.css');
		$this->document->addScript('catalog/view/javascript/ciblog/ciblog.js');

		$data['can_like'] = (($this->config->get('ciblog_store_can_like')=='LOGGED' && $this->customer->isLogged()) || ($this->config->get('ciblog_store_can_like')=='BOTH') );

		$data['blog_row'] = (int)$this->config->get('ciblog_store_blogauthor_row');
		$data['show_title'] = (int)$this->config->get('ciblog_store_blogauthor_show_title');
		$data['show_description'] = (int)$this->config->get('ciblog_store_blogauthor_show_description');
		$data['image_show_listing'] = (int)$this->config->get('ciblog_store_blogauthor_image_show_listing');
		$data['show_date_publish'] = (int)$this->config->get('ciblog_store_blogauthor_show_date_publish');
		$data['show_total_view'] = (int)$this->config->get('ciblog_store_blogauthor_show_total_view');
		$data['show_author'] = (int)$this->config->get('ciblog_store_blogauthor_show_author');
		$data['like_show_total'] = (int)$this->config->get('ciblog_store_blogauthor_like_show_total');
		$data['comment_show_total'] = (int)$this->config->get('ciblog_store_blogauthor_comment_show_total');

		$description_length = (int)$this->config->get('ciblog_store_blogauthor_description_length') ? (int)$this->config->get('ciblog_store_blogauthor_description_length') : 200;
		$date_format = $this->language->get('date_format_short');


		$image_listing_width = (int)$this->config->get('ciblog_store_blogauthor_image_listing_width');
		$image_listing_height = (int)$this->config->get('ciblog_store_blogauthor_image_listing_height');
		if(!$image_listing_width) {
			$image_listing_width = 438;
		}
		if(!$image_listing_height) {
			$image_listing_height = 292;
		}

		if (isset($this->request->get['ciblog_author_id'])) {
			$ciblog_author_id = (int)$this->request->get['ciblog_author_id'];
		} else {
			$ciblog_author_id = 0;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = (int)$this->config->get('ciblog_store_blogauthor_limit') ? (int)$this->config->get('ciblog_store_blogauthor_limit') : $this->config->get($this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_brand'),
			'href' => $this->url->link('extension/ciblog/ciauthor')
		);

		$author_info = $this->model_extension_ciblog_ciauthor->getCiAuthor($ciblog_author_id);

		if ($author_info) {
			$this->document->setTitle($author_info['meta_title']);
			$this->document->setDescription($author_info['meta_description']);
			$this->document->setKeywords($author_info['meta_keyword']);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $author_info['name'],
				'href' => $this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id=' . $this->request->get['ciblog_author_id'] . $url)
			);

			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_postby'] = $this->language->get('text_postby');
			$data['text_on'] = $this->language->get('text_on');
			$data['text_rating'] = $this->language->get('text_rating');

			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_read_more'] = $this->language->get('button_read_more');

			$data['description'] = html_entity_decode($author_info['description'], ENT_QUOTES, 'UTF-8');

			$data['heading_title'] = htmlentities($author_info['name'], ENT_QUOTES, 'UTF-8');


			if ($author_info['image'] && is_file(DIR_IMAGE . $author_info['image'])) {
				$data['thumb'] = $this->model_tool_image->resize($author_info['image'], 100, 100);
			} else {
				$data['thumb'] = '';//$this->model_tool_image->resize('placeholder.png', 100, 100);
			}

			$data['image_title'] = $data['heading_title'];
			$data['image_alt'] = $data['heading_title'];
			if($author_info['image_alt']) {
				$data['image_alt'] = $author_info['image_alt'];
			}
			if($author_info['image_title']) {
				$data['image_title'] = $author_info['image_title'];
			}



			$data['blogposts'] = array();

			$filter_data = array(
				'filter_ciblog_author_id' => $ciblog_author_id,
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
			);

			$blogpost_total = $this->model_extension_ciblog_ciblogpost->getTotalCiBlogPosts($filter_data);

			$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $image_listing_width, $image_listing_height);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $image_listing_width, $image_listing_height);
				}

				$rating = false;
				if((int)$this->config->get('ciblog_store_rating_show')) {
					$rating = (int)$result['rating'];
				}

				$author_info = $this->model_extension_ciblog_ciauthor->getCiAuthor($result['ciblog_author_id']);
				$result['author'] = array();
				$result['author']['name'] = '';
				$result['author']['href'] = '';
				if ($author_info) {
					$result['author']['name'] = htmlentities($author_info['name'], ENT_QUOTES, 'UTF-8');
					$result['author']['href'] = $this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id='. $author_info['ciblog_author_id'] .'&ciblog_post_id=' . $result['ciblog_post_id'] . $url, true);
				}

				// heart static
				$isMyHeart = false;
				$isHearted = $this->model_extension_ciblog_ciblogpost->isHearted($result['ciblog_post_id']);
				if($isHearted) {
					$isMyHeart = true;
				}

				$description = strip_tags(html_entity_decode($result['small_description'], ENT_QUOTES, 'UTF-8'));
				if(strlen($description) > $description_length) {
					$description = utf8_substr($description, 0, $description_length) . '..';
				}

				$data['blogposts'][] = array(
					'ciblog_post_id'  => $result['ciblog_post_id'],
					'thumb'       => $image,
					'name'        => htmlentities($result['name'], ENT_QUOTES, 'UTF-8'),
					'image_title'        => $result['image_title'],
					'image_alt'        => $result['image_alt'],
					'author'        => $result['author'],
					'description' => $description,
					'rating'      => $rating,
					'viewed'      => $result['viewed'],
					'heart'      => $result['heart'],
					'isMyHeart'      => $isMyHeart,
					'comments'      => $result['comments'],
					'add_video_url'      => (int)$result['add_video_url'],
					'video_url'      => $this->ciblog->getVideoURLEmbedURL($result['video_url']),
					'image_thumb_width'      => '100%', //$image_listing_width
					'image_thumb_height'      =>  $image_listing_height.'px',
					'date_added'      => date($date_format, strtotime($result['date_added'])),
					'search_date_added'      => $this->url->link('extension/ciblog/cisearch', 'date='. date('Y-m-d', strtotime($result['date_added'])) . $url, true),
					'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblog_author_id=' . $result['ciblog_author_id'] . '&ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
				);
			}

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $blogpost_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id=' . $this->request->get['ciblog_author_id'] .  $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($blogpost_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($blogpost_total - $limit)) ? $blogpost_total : ((($page - 1) * $limit) + $limit), $blogpost_total, ceil($blogpost_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id=' . $this->request->get['ciblog_author_id'], true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id=' . $this->request->get['ciblog_author_id'], true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id=' . $this->request->get['ciblog_author_id'] . $url . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($blogpost_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id=' . $this->request->get['ciblog_author_id'] . $url . '&page='. ($page + 1), true), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->ciblog->view('extension/ciblog/ciauthor_info', $data));
		} else {
			$url = '';

			if (isset($this->request->get['ciblog_author_id'])) {
				$url .= '&ciblog_author_id=' . $this->request->get['ciblog_author_id'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('extension/ciblog/ciauthor/info', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['header'] = $this->load->controller('common/header');
			$data['footer'] = $this->load->controller('common/footer');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$this->response->setOutput($this->ciblog->view('error/not_found', $data));
		}
	}
}
