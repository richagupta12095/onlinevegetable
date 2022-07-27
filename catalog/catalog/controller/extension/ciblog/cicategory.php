<?php
class ControllerExtensionCiBlogCiCategory extends Controller {
	public function index() {
		$this->load->language('extension/ciblog/cicategory');
		$this->load->language('extension/ciblog/ciblog_common');

		$this->load->model('extension/ciblog/cicategory');
		$this->load->model('extension/ciblog/ciauthor');
		$this->load->model('extension/ciblog/ciblogpost');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/theme/default/stylesheet/ciblog.css');
		$this->document->addScript('catalog/view/javascript/ciblog/ciblog.js');

		$data['can_like'] = (($this->config->get('ciblog_store_can_like')=='LOGGED' && $this->customer->isLogged()) || ($this->config->get('ciblog_store_can_like')=='BOTH') );

		$data['blog_row'] = (int)$this->config->get('ciblog_store_blogcategory_row');
		$data['show_title'] = (int)$this->config->get('ciblog_store_blogcategory_show_title');
		$data['show_description'] = (int)$this->config->get('ciblog_store_blogcategory_show_description');
		$data['image_show_listing'] = (int)$this->config->get('ciblog_store_blogcategory_image_show_listing');
		$data['show_date_publish'] = (int)$this->config->get('ciblog_store_blogcategory_show_date_publish');
		$data['show_total_view'] = (int)$this->config->get('ciblog_store_blogcategory_show_total_view');
		$data['show_author'] = (int)$this->config->get('ciblog_store_blogcategory_show_author');
		$data['like_show_total'] = (int)$this->config->get('ciblog_store_blogcategory_like_show_total');
		$data['comment_show_total'] = (int)$this->config->get('ciblog_store_blogcategory_comment_show_total');

		$description_length = (int)$this->config->get('ciblog_store_blogcategory_description_length') ? (int)$this->config->get('ciblog_store_blogcategory_description_length') : 200;
		$date_format = $this->language->get('date_format_short');


		$image_listing_width = (int)$this->config->get('ciblog_store_blogcategory_image_listing_width');
		$image_listing_height = (int)$this->config->get('ciblog_store_blogcategory_image_listing_height');
		if(!$image_listing_width) {
			$image_listing_width = 438;
		}
		if(!$image_listing_height) {
			$image_listing_height = 292;
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
			$limit = (int)$this->config->get('ciblog_store_blogcategory_limit') ? (int)$this->config->get('ciblog_store_blogcategory_limit') : $this->config->get($this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['ciblogpath'])) {
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

			$ciblogpath = '';

			$parts = explode('_', (string)$this->request->get['ciblogpath']);

			$ciblog_category_id = (int)array_pop($parts);

			foreach ($parts as $ciblogpath_id) {
				if (!$ciblogpath) {
					$ciblogpath = (int)$ciblogpath_id;
				} else {
					$ciblogpath .= '_' . (int)$ciblogpath_id;
				}

				$category_info = $this->model_extension_ciblog_cicategory->getCiCategory($ciblogpath_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $ciblogpath . $url)
					);
				}
			}
		} else {
			$ciblog_category_id = 0;
		}

		$category_info = $this->model_extension_ciblog_cicategory->getCiCategory($ciblog_category_id);

		if ($category_info) {

			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];

			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');

			$data['text_postby'] = $this->language->get('text_postby');
			$data['text_on'] = $this->language->get('text_on');
			$data['text_rating'] = $this->language->get('text_rating');

			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_read_more'] = $this->language->get('button_read_more');

			// Set the last cicategory breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $this->request->get['ciblogpath'])
			);

			if ($category_info['image'] && is_file(DIR_IMAGE . $category_info['image'])) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
			} else {
				$data['thumb'] = '';//$this->model_tool_image->resize('placeholder.png', 100, 100);
			}

			$data['image_title'] = $data['heading_title'];
			$data['image_alt'] = $data['heading_title'];
			if($category_info['image_alt']) {
				$data['image_alt'] = $category_info['image_alt'];
			}
			if($category_info['image_title']) {
				$data['image_title'] = $category_info['image_title'];
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

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

			$data['cicategories'] = array();

			$results = $this->model_extension_ciblog_cicategory->getCiCategories($ciblog_category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_ciblog_category_id'  => $result['ciblog_category_id'],
					'filter_sub_cicategory' => true
				);

				if ($result['image'] && is_file(DIR_IMAGE . $result['image'])) {
					$thumb = $this->model_tool_image->resize($result['image'], 50, 50);
				} else {
					$thumb = '';// $this->model_tool_image->resize('placeholder.png', 50, 50);
				}

				$data['cicategories'][] = array(
					'thumb' => $thumb,
					'image_alt' => $result['image_alt'] ? $result['image_alt'] : $result['name'],
					'image_title' => $result['image_title'] ? $result['image_title'] : $result['name'],
					'name' => $result['name'] . ($this->config->get('config_ciblog_post_count') ? ' (' . $this->model_extension_ciblog_ciblogpost->getTotalCiBlogPosts($filter_data) . ')' : ''),
					'href' => $this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $this->request->get['ciblogpath'] . '_' . $result['ciblog_category_id'] . $url)
				);
			}

			$data['blogposts'] = array();

			$filter_data = array(
				'filter_ciblog_category_id' => $ciblog_category_id,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$ciblog_post_total = $this->model_extension_ciblog_ciblogpost->getTotalCiBlogPosts($filter_data);

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
					$result['author']['href'] = $this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id='. $author_info['ciblog_author_id'] . $url, true);
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
					'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblogpath=' . $this->request->get['ciblogpath'] . '&ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
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
			$pagination->total = $ciblog_post_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $this->request->get['ciblogpath'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($ciblog_post_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($ciblog_post_total - $limit)) ? $ciblog_post_total : ((($page - 1) * $limit) + $limit), $ciblog_post_total, ceil($ciblog_post_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $category_info['ciblog_category_id'], true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $category_info['ciblog_category_id'], true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $category_info['ciblog_category_id'] . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($ciblog_post_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $category_info['ciblog_category_id'] . '&page='. ($page + 1), true), 'next');
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

			$this->response->setOutput($this->ciblog->view('extension/ciblog/cicategory', $data));
		} else {
			$url = '';

			if (isset($this->request->get['ciblogpath'])) {
				$url .= '&ciblogpath=' . $this->request->get['ciblogpath'];
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
				'href' => $this->url->link('extension/ciblog/cicategory', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->ciblog->view('error/not_found', $data));
		}
	}
}
